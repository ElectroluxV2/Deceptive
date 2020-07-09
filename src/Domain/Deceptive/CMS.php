<?php


namespace App\Domain\Deceptive;


use App\Domain\Facebook\FacebookPageManager;
use App\Domain\Google\Photos\GooglePhotosManager;
use App\Domain\Regatta\RegattaManager;
use Facebook\Exceptions\FacebookSDKException;
use Medoo\Medoo;
use Psr\Log\LoggerInterface;
use stdClass;

class CMS {

    /**
     * @var GooglePhotosManager
     */
    private GooglePhotosManager $photosManager;
    /**
     * @var FacebookPageManager
     */
    private FacebookPageManager $pageManager;
    /**
     * @var Medoo
     */
    private Medoo $database;
    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;
    /**
     * @var RegattaManager
     */
    private RegattaManager $regattaManager;

    /**
     * CMS constructor.
     * @param LoggerInterface $logger
     * @param Medoo $database
     * @param FacebookPageManager $pageManager
     * @param GooglePhotosManager $photosManager
     * @param RegattaManager $regattaManager
     */
    public function __construct(LoggerInterface $logger, Medoo $database, FacebookPageManager $pageManager, GooglePhotosManager $photosManager, RegattaManager $regattaManager) {
        $this->logger = $logger;
        $this->database = $database;
        $this->pageManager = $pageManager;
        $this->photosManager = $photosManager;
        $this->regattaManager = $regattaManager;
    }

    /**
     * @return RegattaManager
     */
    public function getRegattaMgr(): RegattaManager {
        return $this->regattaManager;
    }

    /**
     * @param string $title
     * @param stdClass $body
     * @return int
     * @throws FacebookSDKException
     */
    public function newPost(string $title, stdClass $body): int {

        // We have to patch whole body
        $toPatch = $body->rawHTML;

        // Remove CKE class="pen-red"
        $toPatch = str_replace(' class="pen-red"', '', $toPatch);
        // CKE <figure class="table">
        $toPatch = str_replace('<figure class="table">', '', $toPatch);
        $toPatch = str_replace('</table></figure>', '</table>', $toPatch);
        $toPatch = str_replace('style="text-align:right;"', 'class="right"', $toPatch);
        $toPatch = str_replace('style="text-align:left;"', 'class="left"', $toPatch);
        $toPatch = str_replace('style="text-align:center;"', 'class="center"', $toPatch);
        $toPatch = str_replace('style="text-align:justify;"', 'class="justify"', $toPatch);
        // CKE <figure class="media">
        $toPatch = str_replace('<figure class="media">', '', $toPatch);
        $toPatch = str_replace('</oembed></figure>', '</oembed>', $toPatch);

        $changeLinkToUrl = function ($matches) {
            return sprintf('https://www.facebook.com/plugins/video.php?show_text=false&href=%s', urlencode($matches[0]));
        };

        // FB videos
        $toPatch = preg_replace_callback('/(https:\/\/www\.facebook\.com\/([\S]*)\/videos\/([0-9]*))/m', $changeLinkToUrl, $toPatch);
        $toPatch = preg_replace_callback('/(https:\/\/www\.facebook\.com\/watch\/\?v=([0-9]*))/m', $changeLinkToUrl, $toPatch);


        $galleries = [];
        // Find galleries
        while (strpos($toPatch, '<figure class="image">')) {
            $start = strpos($toPatch, '<figure class="image">');
            $stop = strpos($toPatch, '</figure>') + 9;

            // Extract ID
            $gallery = substr($toPatch, $start, $stop);
            $idStart = strpos($gallery, 'alt="') + 5;
            $gallery = substr($gallery, $idStart);
            $idStop = strpos($gallery, '"><f');
            $galleryID = substr($gallery, 0, $idStop);

            // Get images
            array_push($galleries, [
                'id' => $galleryID,
                'data' =>  $this->pageManager->getAlbumContents($galleryID)
            ]);

            // Remove from body and add Gallery tag
            $galleryTag = 'G[<'.$galleryID.'>]G';
            $toPatch = substr($toPatch, 0, $start).$galleryTag.substr($toPatch, $stop);
        }

        // Save post
        $this->database->insert('posts', [
            'title' => $title,
            'body' => $toPatch
        ]);
        $postID = $this->database->id();
        $this->logger->info('L: ', $this->database->log());

        return $postID;
    }

    /**
     * Saves Facebook image in DB
     * @param $ID int Id of Facebook Image to save
     * @return int Id of image in DB
     * @throws FacebookSDKException
     */
    public function parseFBImage($ID): int {
        $image = $this->pageManager->getPhoto($ID);

        // Save image
        $this->database->insert('images', [
            'gallery_id' => $image['galleryID'],
            'facebook_id' => $image['id']
        ]);
        $imageID = $this->database->id();

        $comboInsert = [];
        foreach ($image['sizes'] as $size) {
            // Save size
            array_push($comboInsert, [
                'height' => $size['height'],
                'width' => $size['width'],
                'jpg' => $size['jpg'],
                'webp' => $size['webp'],
                'image_id' => $imageID
            ]);
        }

        $this->database->insert('sizes', $comboInsert);

        $this->logger->info('log', $this->database->log());

        return $imageID;
    }

    /**
     * @param $limit int How many posts to show at once
     * @param $after int page number
     * @return array Posts
     */
    public function getPosts($limit, $after): array {
        $data = [
            'posts' => [],
            'next' => ''
        ];

        $data['posts'] = $this->database->select('posts',[
            'id',
            'title',
            'body'
        ], [
            "LIMIT" => $limit
        ]);

        $data['next'] = '/'.$after;

        return $data;
    }


}