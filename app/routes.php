<?php
declare(strict_types=1);

use App\Application\Actions\Deceptive\Post\CreateAction as CreatePost;
use App\Application\Actions\Deceptive\Download\StreamFileAction as DownloadFile;
use App\Application\Actions\Deceptive\Declaration\GenerateAction as GenerateDeclaration;
use App\Application\Actions\Deceptive\Post\ListAction as ListPosts;
use App\Application\Actions\Deceptive\Image\TakeFBImageAction as ParseFBImage;
use App\Application\Actions\Deceptive\Declaration\ShowAction as ShowDeclaration;
use App\Application\Actions\Facebook\Album\PushPhotoAction as AddPhotoToAlbumFB;
use App\Application\Actions\Facebook\Album\CreateAction as CreateAlbumFB;
use App\Application\Actions\Facebook\Album\ListAction as ListAlbumsFB;
use App\Application\Actions\Facebook\Album\ListContentsAction as ListAlbumFB;
use App\Application\Actions\Facebook\WebHooks\VerifyHookAction as VerifyHookFB;
use App\Application\Actions\GooglePhotos\Photo\GetAction as GetPhotoGP;
use App\Application\Actions\GooglePhotos\Photo\GetBatchAction as GetPhotoBatchGP;
use App\Application\Actions\GooglePhotos\Album\ListPhotosAction as GetPhotosInsideAlbumGP;
use App\Application\Actions\GooglePhotos\Album\ListAction as ListAlbumsGP;
use App\Application\Actions\GooglePhotos\Photo\ListUserPhotosAction as ListContentsGP;
use App\Application\Actions\User\ListUsersAction as ListUsers;
use App\Application\Actions\User\ViewUserAction as ViewUser;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    $app->group('/GooglePhotos', function (Group $googlePhotosGroup) {

        $googlePhotosGroup->group('/albums', function (Group $albumGroup) {
            $albumGroup->map(['GET', 'OPTIONS'], '/get/{id}[/{pageToken}]', GetPhotosInsideAlbumGP::class);
            $albumGroup->map(['GET', 'OPTIONS'], '/list[/{pageToken}]', ListAlbumsGP::class);
        });

        $googlePhotosGroup->group('/photos', function (Group $photoGroup) {
            $photoGroup->map(['GET', 'OPTIONS'],'/list[/{pageToken}]', ListContentsGP::class);
            $photoGroup->map(['GET', 'OPTIONS'], '/get/{id}', GetPhotoGP::class);
            $photoGroup->map(['POST', 'OPTIONS'],'/getBatch', GetPhotoBatchGP::class);
        });

    });

    $app->group('/Facebook', function (Group $facebookGroup) {

        $facebookGroup->group('/albums', function (Group $albumGroup) {
            $albumGroup->map(['GET', 'OPTIONS'], '/list[/{pageToken}]', ListAlbumsFB::class);
            $albumGroup->map(['GET', 'OPTIONS'], '/create/{name}', CreateAlbumFB::class);
            $albumGroup->map(['GET', 'OPTIONS'], '/get/{id}', ListAlbumFB::class);
            $albumGroup->map(['POST', 'OPTIONS'], '/add/{id}', AddPhotoToAlbumFB::class);
        });


        $facebookGroup->group('/webhooks', function (Group $weebhooks) {
           $weebhooks->map(['GET', 'OPTIONS'], '/verify', VerifyHookFB::class);
        });

    });

    $app->group('/Users', function (Group $group) {
        $group->map(['GET', 'OPTIONS'], '/list', ListUsers::class);
        $group->map(['GET', 'OPTIONS'], '/current', ViewUser::class);
    });

    $app->group('/Deceptive', function (Group $deceptiveGroup) {

        $deceptiveGroup->group('/posts', function (Group $postsGroup) {
            $postsGroup->map(['POST', 'OPTIONS'], '/create/{name}', CreatePost::class);
            $postsGroup->map(['GET', 'OPTIONS'], '/list/{limit}[/{pageToken}]', ListPosts::class);
        });

        $deceptiveGroup->group('/image', function (Group $imageGroup) {
            $imageGroup->group('/parse', function (Group $parseGroup) {
               $parseGroup->map(['GET', 'OPTIONS'], '/facebook/{id}', ParseFBImage::class);
            });
        });

        $deceptiveGroup->group('/declaration', function (Group $declarationGroup) {
            $declarationGroup->map(['POST', 'OPTIONS'], '/generate', GenerateDeclaration::class);
            $declarationGroup->map(['GET', 'OPTIONS'], '/show/{id}', ShowDeclaration::class);
        });

        $deceptiveGroup->group('/file', function (Group $fileGroup) {
           $fileGroup->map(['GET', 'OPTIONS'],'/download/{id}', DownloadFile::class);
        });
    });
};
