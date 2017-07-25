<?php
namespace Orbas\Stage\Traits;

trait Presenter
{
    /**
     * @var \Orbas\Stage\Presenter
     */
    protected $presenterInstance;
    
    /**
     * get presenter class name
     * override this method if your presenter name is different 
     * than model class name
     * 
     * @return string
     */
    protected function getPresenter()
    {
        return 'App\\Presenters\\' . class_basename($this);
    }

    /**
     * @param string|null $name
     *
     * @return \Orbas\Stage\Presenter|string|int
     */
    public function present($name = null)
    {
        if (!$this->presenterInstance) {
            $class = $this->getPresenter();
            $this->presenterInstance = app($class, ['entity' => $this]);
        }
        
        if ($name) {
            return $this->presenterInstance->$name;
        }
        
        return $this->presenterInstance;
    }
}