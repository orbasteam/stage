<?php

namespace Orbas\Stage\Http\Controllers;

use Illuminate\Routing\Controller;
use Orbas\Stage\AppException;
use File;

class AssetsController extends Controller
{
    /**
     * get resource path
     * 
     * @param $path
     *
     * @return string
     */
    protected function resourcePath($path)
    {
        return config('stage.root') . '/../resources/' . ltrim($path, '/') ;
    }

    /**
     * load javascript assets
     * 
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws AppException
     */
    public function javascript()
    {
        $path = $this->resourcePath('dist/app.js');

        if (!file_exists($path)) {
            throw new AppException('Assets haven\'t build yet, please run "npm run production"');
        }

        return $this->readFile($path, 'application/javascript');
    }

    /**
     * load css assets
     * 
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws AppException
     */
    public function css()
    {
        $path = $this->resourcePath('dist/app.css');

        if (!file_exists($path)) {
            throw new AppException('Assets haven\'t build yet, please run "npm run production"');
        }
        
        return $this->readFile($path, 'text/css');
    }

    /**
     * load images
     * 
     * @param $image
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function images($image)
    {
        $path = $this->resourcePath('assets/images/' . $image);

        if (File::exists($path)) {
            return $this->readFile($path, File::mimeType($path));
        }
        
        return abort('404');
    }

    protected function readFile($path, $contentType)
    {
        return response(File::get($path), 200)
            ->header('Cache-Control', 'max-age=31536000')
            ->header('Content-Type', $contentType)
            ->setEtag(md5_file($path));
    }
}