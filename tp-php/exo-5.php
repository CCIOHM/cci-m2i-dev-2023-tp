<?php

// ------------
// EXO 5 ONLY !
// ------------

// NOT RUN DIRECTLY
// THIS IS JUST A EXAMPLE

require 'vendor/autoload.php';

use Aws\S3\S3Client;
use Aws\CloudFront\CloudFrontClient;

// Configuration pour S3
$s3 = new S3Client([
    'version'     => 'latest',
    'region'      => $_ENV['AWS_S3_REGION'],
    'credentials' => [
        'key'    => $_ENV['AWS_S3_ACCESS_KEY_ID'],
        'secret' => $_ENV['AWS_S3_SECRET_ACCESS_KEY'],
    ],
]);

$file_name = "My Resume";
// Fetching cloudfront public key Id from .env file
$publicKeyId = $_ENV['AWS_CLOUDFRONT_PUBLIC_KEY_ID'];
// Fetching s3 bucket name from .env file
// Fetch cloudfront private key name from .env file
// Read the private key file from s3 bucket
$s3ReadFile = [
    'Bucket' => $_ENV['AWS_S3_BUCKET'],
    'Key'    => $_ENV['AWS_CLOUDFRONT_PRIVATE_KEY_FILE_NAME'],
];

// Private key file read from s3
try {
    $responseFromS3 = $s3->getObject($s3ReadFile);

    // Converting binary content to string
    $privateKey = $responseFromS3['Body']->getContents();

    $cloudFront = new CloudFrontClient([
        'version' => 'latest',
        'region'  => 'us-east-1' // CloudFront API calls require us-east-1
    ]);

    $signer = new Aws\CloudFront\UrlSigner($publicKeyId, $privateKey);

    $signedUrl = $signer->getSignedUrl([
        'url'     => $_ENV['AWS_CLOUDFRONT_URL_TO_ACCESS_S3_OBJECT'] . '/' . $file_name,
        // Link expiration duration
        'expires' => time() + 300 // 5 minutes from now
    ]);

    if (!$signedUrl) {
        // Handle error in getting presigned URL Read Response
    } else {
        // Success: Handle the signed URL
    }
} catch (Aws\Exception\AwsException $e) {
    // Handle error from S3
}


