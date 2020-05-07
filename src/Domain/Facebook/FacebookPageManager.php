<?php


namespace App\Domain\Facebook;


use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;

class FacebookPageManager {

    /**
     * @var Facebook
     */
    private $facebook;

    /**
     * @var string
     */
    private $pageID;

    /**
     * FacebookPageManager constructor.
     * @param Facebook $facebook
     */
    public function __construct(Facebook $facebook) {
        $this->facebook = $facebook;
        $this->pageID = "102176778044423";
    }

    /**
     * @return array
     * @throws FacebookSDKException
     */
    public function getAlbums(): array {
        try {
            $data = ['albums' => []];
            $facebookResponse = $this->facebook->get('/' . $this->pageID . '/albums?fields=name,id,privacy,count,picture.type(album)');
            $albumsEdge = $facebookResponse->getGraphEdge();

            foreach ($albumsEdge as $albumNode) {
                $album = [
                    "name" => $albumNode['name'],
                    "mediaItemsCount" => $albumNode['count'],
                    "privacy" => $albumNode['privacy'],
                    "id" => $albumNode['id'],
                    "coverPhotoUrl" => $albumNode['picture']['url']
                ];

                array_push($data['albums'], $album);
            }

            return $data;

        } catch (FacebookSDKException $e) {
            throw $e;
        }
    }

    /**
     * @param $id
     * @return int
     * @throws FacebookSDKException
     */
    public function addPhotoToAlbum($id): int {
        try {
            $url = json_decode(file_get_contents('php://input'))->url;

            $facebookResponse = $this->facebook->post('/' . $id . '/photos', [
                    'url' => $url,
                    'no_story' => true
                ]
            );

            $graphNode = $facebookResponse->getGraphNode();
            return $graphNode['id'];

        } catch (FacebookSDKException $e) {
            throw $e;
        }
    }

    /**
     * @param $id
     * @return array
     * @throws FacebookSDKException
     */
    public function getAlbumContents($id): array {
        try {
            $data = ['images' => []];
            $facebookResponse = $this->facebook->get('/' . $id . '/photos?fields=images,webp_images,id,link');

            $imagesEdge = $facebookResponse->getGraphEdge();

            foreach ($imagesEdge as $imageNode) {

                $image = [
                    'link' => $imageNode['link'],
                    'id' => $imageNode['id'],
                    'sizes' => [],
                ];

                for ($i = 0; $i < count($imageNode['images']); $i++) {
                    $size = $imageNode['images'][$i];
                    $webp = $imageNode['webp_images'][$i];
                    array_push($image['sizes'], [
                        'height' => $size['height'],
                        'width' => $size['width'],
                        'jpg' => $size['source'],
                        'webp' => $webp['source'],
                    ]);
                }

                array_push($data['images'], $image);
            }

            $body = $facebookResponse->getDecodedBody();
            if (isset($body['paging']['next'])) {
                    $next = $data['nextPageToken'] = $body['paging']['next'];
            }
            return $data;

        } catch (FacebookSDKException $e) {
            throw $e;
        }
    }

    /**
     * @param $name
     * @return int
     * @throws FacebookSDKException
     */
    public function createAlbum($name): int {
        try {
            $facebookResponse = $this->facebook->post('/' . $this->pageID . '/albums', [
                    'name' => $name
                ]
            );

            $graphAlbum = $facebookResponse->getGraphAlbum();
            return $graphAlbum->getId();

        } catch (FacebookSDKException $e) {
            throw $e;
        }
    }

    /**
     * @param int $ID
     * @return array
     * @throws FacebookSDKException
     */
    public function getPhoto(int $ID): array {
        try {
            $facebookResponse = $this->facebook->get('/' . $ID . '?fields=album,images,webp_images');

            $graphImage = $facebookResponse->getGraphNode();
            $img = $graphImage->asArray();

            $r = [
                'galleryID' => $img['album']['id'],
                'id' => $img['id'],
                'sizes' => []
            ];

            for ($i = 0; $i < count($img['images']); $i++) {
                $size = $img['images'][$i];
                $webp = $img['webp_images'][$i];
                array_push($r['sizes'], [
                    'height' => $size['height'],
                    'width' => $size['width'],
                    'jpg' => $size['source'],
                    'webp' => $webp['source'],
                ]);
            }

            return $r;

        } catch (FacebookSDKException $e) {
            throw $e;
        }
    }
}