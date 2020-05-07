<?php


namespace App\Domain\Google\Photos;

use Google\ApiCore\ApiException;
use Google\ApiCore\ValidationException;
use Google\Photos\Library\V1\PhotosLibraryClient;
use Google\Photos\Types\MediaItem;
use Google\Photos\Types\Photo;
use stdClass;

class GooglePhotosManager {

    /**
     * @var PhotosLibraryClient
     */
    private $client;

    /**
     * GooglePhotosManager constructor.
     * @param PhotosLibraryClient $client
     */
    public function __construct(PhotosLibraryClient $client) {
        $this->client = $client;
    }

    /**
     * @param null $pageToken
     * @return array
     * @throws GooglePhotosException
     */
    public function getAlbums($pageToken = null): array {
        try {
            $data = [ 'albums' => []];
            $options = ['pageSize' => 50];

            if ($pageToken !== null) {
                $options['pageToken'] = $pageToken;
            }

            $pagedResponse = $this->client->listAlbums($options);
            $page = $pagedResponse->getPage();

            while (($page->hasNextPage()) && ($page->getPageElementCount() === 0)) {
                $page = $page->getNextPage();
            }

            foreach ($page as $album) {
                $id = $album->getId();
                $title = $album->getTitle();
                $productUrl = $album->getProductUrl();
                $coverPhotoBaseUrl = $album->getCoverPhotoBaseUrl();
                $coverPhotoMediaItemId = $album->getCoverPhotoMediaItemId();
                $isWriteable = $album->getIsWriteable();
                $mediaItemsCount = $album->getMediaItemsCount();

                array_push($data['albums'], [
                    "title" => $title,
                    "coverPhotoBaseUrl" => $coverPhotoBaseUrl,
                    "mediaItemsCount" => $mediaItemsCount,
                    "id" => $id
                ]);
            }

            if ($page->hasNextPage()) {
                $data['nextPageToken'] = $page->getNextPageToken();
            }

            return $data;

        } catch (ApiException $e) {
            throw new GooglePhotosException($e->getMessage());
        } catch (ValidationException $e) {
            throw new GooglePhotosException($e->getMessage());
        }
    }

    /**
     * @param $item MediaItem
     * @return stdClass
     */
    private function parseMediaItem($item): stdClass {
        $image = new stdClass();
        $image->id = $item->getId();
        $image->description = $item->getDescription();
        $image->baseUrl = $item->getBaseUrl();
        $image->productUrl = $item->getProductUrl();
        $image->mimeType = $item->getMimeType();

        $metadata = $item->getMediaMetadata();
        if (!is_null($metadata)) {
            $image->height = $metadata->getHeight();
            $image->width = $metadata->getWidth();
            $image->creationTime = $metadata->getCreationTime();

            $photoMetadata = $metadata->getPhoto();
            if (!is_null($photoMetadata)) {
                $image->cameraMake = $photoMetadata->getCameraMake();
                $image->cameraModel = $photoMetadata->getCameraModel();
                $image->aperture = $photoMetadata->getApertureFNumber();
                $image->isoEquivalent = $photoMetadata->getIsoEquivalent();
                $image->exposureTime = $photoMetadata->getExposureTime();
            }
        }
        $contributorInfo = $item->getContributorInfo();
        if (!is_null($contributorInfo)) {
            $image->contributorProfilePictureBaseUrl = $contributorInfo->getProfilePictureBaseUrl();
            $image->contributorDisplayName = $contributorInfo->getDisplayName();
        }

        return $image;
    }

    /**
     * @param $id
     * @return stdClass
     * @throws GooglePhotosException
     */
    public function getPhoto($id): stdClass {
        try {
            $item = $this->client->getMediaItem($id);

            return $this->parseMediaItem($item);
            
        } catch (ApiException $e) {
            throw new GooglePhotosException($e->getMessage());
        }
    }

    /**
     * @param array $ids
     * @return array
     * @throws GooglePhotosException
     */
    public function getPhotos(array $ids): array {
        try {
            $data = ['images' => []];
            $response = $this->client->batchGetMediaItems($ids);

            foreach ($response->getMediaItemResults() as $itemResult) {

                $item = $itemResult->getMediaItem();
                if (is_null($item)){
                    continue;
                }

                array_push($data['images'], $this->parseMediaItem($item));
            }

            return $data;

        } catch (ApiException $e) {
            throw new GooglePhotosException($e->getMessage());
        }
    }

    /**
     * @param null $pageToken
     * @return array
     * @throws GooglePhotosException
     */
    public function getContents($pageToken = null): array {
        try {
            $data = ['images' => []];
            $options = ['pageSize' => 50];

            if ($pageToken !== null) {
                $options['pageToken'] = $pageToken;
            }

            $pagedResponse = $this->client->listMediaItems($options);

            $page = $pagedResponse->getPage();

            while (($page->hasNextPage()) && ($page->getPageElementCount() == 0)) {
                $page = $page->getNextPage();
            }

            foreach ($page as $item) {
                $id = $item->getId();
                $description = $item->getDescription();
                $mimeType = $item->getMimeType();
                $productUrl = $item->getProductUrl();
                $filename = $item->getFilename();
                $baseUrl = $item->getBaseUrl();

                array_push($data['images'], [
                    "productUrl" => $productUrl,
                    "baseUrl" => $baseUrl,
                    "mimeType" => $mimeType,
                    "description" => $description,
                    "id" => $id,
                ]);
            }

            if ($page->hasNextPage()) {
                $data['nextPageToken'] = $page->getNextPageToken();
            }

            return $data;

        } catch (ApiException $e) {
            throw new GooglePhotosException($e->getMessage());
        } catch (ValidationException $e) {
            throw new GooglePhotosException($e->getMessage());
        }
    }

    /**
     * @param $id
     * @param null $pageToken
     * @return array
     * @throws GooglePhotosException
     */
    public function getAlbumContents($id, $pageToken = null): array {
        try {
            $data = ['images' => []];
            $options = ['albumId' => $id, 'pageSize' => 100];

            if ($pageToken !== null) {
                $options['pageToken'] = $pageToken;
            }

            $pagedResponse = $this->client->searchMediaItems($options);
            $page = $pagedResponse->getPage();

            while (($page->hasNextPage()) && ($page->getPageElementCount() == 0)) {
                $page = $page->getNextPage();
            }

            foreach ($page as $photo) {

                $mediaMetadata = $photo->getMediaMetadata();
                array_push($data['images'], [
                    'file_name' => $photo->getFilename(),
                    'width' => $mediaMetadata->getWidth(),
                    'height' => $mediaMetadata->getHeight(),
                    'baseUrl' => $photo->getBaseUrl()
                ]);
            }

            if ($page->hasNextPage()) {
                $data['nextPageToken'] = $page->getNextPageToken();
            }

            return $data;

        } catch (ApiException $e) {
            throw new GooglePhotosException($e->getMessage());
        } catch (ValidationException $e) {
            throw new GooglePhotosException($e->getMessage());
        }
    }
}