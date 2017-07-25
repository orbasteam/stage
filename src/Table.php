<?php
namespace Orbas\Stage;

use Illuminate\Config\Repository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Orbas\Stage\Table\Body;
use Orbas\Stage\Table\Header;

class Table
{
    const DEFAULT_GROUP = 'default';
    
    /**
     * @var Repository
     */
    protected $config;

    /**
     * @var string
     */
    protected $group = self::DEFAULT_GROUP;
    
    /**
     * @var Model
     */
    protected $model;

    /**
     * @var Collection
     */
    protected $header;

    /**
     * @var Collection
     */
    protected $body;

    /**
     * @var string
     */
    protected $defaultView = 'table::bootstrap-3';

    /**
     * Table constructor.
     *
     * @param string $model
     * @param string $group
     */
    public function __construct($model = null, $group = self::DEFAULT_GROUP)
    {
        if (!$model) {
            $model = $this->parseModelNameFromController();
        }
        
        $this->model = app($model);
        $this->group = $group;
        
        Debug::log($model, 'Model');
        Debug::log($group, 'Table Group');
    }

    /**
     * @param string|null $key
     *
     * @return mixed
     */
    public function getConfig($key = null)
    {
        if (!$this->config) {
            $this->initConfig();
        }
        
        if ($key) {
            return $this->config->get($key);
        }
        
        return $this->config;
    }

    protected function initConfig()
    {
        $config = app('stage.storage')->get($this->getTableName());
        $this->config = new Repository($config->toArray());
    }

    /**
     * @return string
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * 
     * @return Model
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @return string
     */
    public function getTableName()
    {
        return $this->getModel()->getTable();
    }

    /**
     * Generate default model name
     */
    protected function parseModelNameFromController()
    {
        $controller = class_basename(get_class(request()->route()->getController()));
        $model = substr($controller, 0, strpos($controller, 'Controller'));

        return config('stage.global.modelNamespace') . studly_case(str_singular($model));
    }

    /**
     * @return Collection
     */
    public function getHeader()
    {
        if (!$this->header) {
            $this->header = (new Header($this))->items();
        }

        return $this->header;
    }

    /**
     * @return Collection
     */
    public function getBody()
    {
        if (!$this->body) {
            $this->body = (new Body($this))->items();
        }
        
        return $this->body;
    }

    /**
     * render table
     *
     * @param string|null $view
     *
     * @return string
     */
    public function render($view = null)
    {
        if (is_null($view)) {
            $view = $this->defaultView;
        }

        return view($view)->with('table', $this)->render();
    }
}