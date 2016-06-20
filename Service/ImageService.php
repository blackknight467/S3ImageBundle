<?php

namespace blackknight467\S3ImageBundle\Service;

use Aws\Credentials\Credentials;
use Aws\S3\S3Client;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageService
{
    /**
     * @var S3Client
     */
    private $s3Client;

    /**
     * @var string
     */
    private $uploadBucket;

    /**
     * @var string
     */
    private $readBucket;

    /**
     * @var string
     */
    private $cdn;

    /**
     * @var string
     */
    private $localTempImageStorage;

    /**
     * @var EntityManager
     */
    private $em;

    public function __construct($key, $secret, $region, $uploadBucket, $readBucket, $cdn, $localTempImageStorage, $rootPath, EntityManager $em)
    {
        $this->uploadBucket = $uploadBucket;
        $this->readBucket = $readBucket;
        $this->cdn = $cdn;
        $this->localTempImageStorage = $localTempImageStorage;
        $this->em = $em;
        $this->rootPath = $rootPath;
        $credentials = new Credentials($key, $secret);
        $this->s3Client = new S3Client([
            'version' => 'latest',
            'region'  => $region,
            'credentials' => $credentials
        ]);
    }

    /**
     * Save image info to the db and upload to S3 upload bucket
     *
     * @param UploadedFile $imageData
     * @param string $directory
     * @param null $image
     *
     * @return Image|bool|null
     * @throws \Exception
     */
    public function save(UploadedFile $imageData, $directory = 'default', $image = null)
    {
        $extension = $imageData->guessExtension();
        $localName = $imageData->getFilename();
        $localPath = $imageData->getPathname();

        // get height and width of image temporarily stored on the local server
        $sizeInfo = getImagesize($localPath);

        // create image and fill out its details
        if (is_null($image)) {
            $image = new Image();
        }

        $image->setName($localName);
        $image->setSize($imageData->getSize());
        $image->setPath($localPath);
        $image->setCaption('');
        $image->setWidth($sizeInfo[0]);
        $image->setHeight($sizeInfo[1]);

        // save image to generate id
        $this->em->persist($image);
        $this->em->flush();

        // generate name based on image id
        $imageName = sprintf('%s.%s', $image->getId(), $extension);
        $image->setName($imageName);

        if(!empty($rootPath)) {
            $rootPath = $this->rootPath;
            //add trailing slash if missing
            $lastchar = substr($rootPath, -strlen('/'));
            if ($lastchar !== '/') {
                $rootPath = $rootPath . '/';
            }
            $imagePath = $rootPath . $directory . '/';
            $image->setPath($imagePath);
        } else {
            $image->setPath('/');
        }

        if (!empty($this->localTempImageStorage)) {
            $uploadDirectory = $this->localTempImageStorage;
            $tempPath = $uploadDirectory . $imageName;
        } else {
            $tempPath = sprintf('%s/uploads/%s', getcwd(), $imageName);
        }

        // handles file uploaded via a form
        $success = true;
        if (is_uploaded_file($localPath)) {
            $success = move_uploaded_file($localPath, $tempPath);
            if (!$success) {
                throw new \Exception('Failed up upload file');
            }
        }
        // handles regular files
        else {
            $sucess = rename($localPath, $tempPath);
            if (!$sucess) {
                throw new \Exception('Failed up rename file');
            }
        }

        if ($success) {
            $this->upload($tempPath, $image->getPath(), $imageName);

            // save image
            $this->em->persist($image);
            $this->em->flush();

            return $image;
        }

        return false;
    }

    /**
     * @param $fromPath
     * @param $toPath
     */
    public function uploadImageToS3($fromPath, $toPath)
    {
        // Upload a publicly accessible file. The file size and type are determined by the SDK.
        try {
            $this->s3Client->putObject([
                'Bucket' => $this->uploadBucket,
                'Key'    => $toPath,
                'Body'   => fopen($fromPath, 'r'),
                'ACL'    => 'public-read',
            ]);
        } catch (\Exception $e) {
            echo "There was an error uploading the file.\n";
        }

        // We can poll the object until it is accessible
        $this->s3Client->waitUntil('ObjectExists', array(
            'Bucket' => $this->uploadBucket,
            'Key'    => $toPath
        ));
    }
}