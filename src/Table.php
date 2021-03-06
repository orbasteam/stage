<?php
namespace Orbas\Stage;

use Closure;
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
     * @var Closure
     */
    protected $filter;
    
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
        
        return $key ? $this->config->get($key) : $this->config;
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
     * @return Header
     */
    public function getHeader()
    {
        if (!$this->header) {
            $this->header = new Header($this);
        }

        return $this->header;
    }

    /**
     * @param Closure $callback
     *
     * @return $this
     */
    public function filter(Closure $callback)
    {
        $this->filter = $callback;
        return $this;
    }

    /**
     * @return Closure
     */
    public function getFilter()
    {
        return $this->filter;
    }

    /**
     * @return Body
     */
    public function getBody()
    {
        if (!$this->body) {
            $this->body = (new Body($this));
        }
        
        return $this->body;
    }

    /**
     * @return mixed
     */
    public function enablePaginate()
    {
        $group = $this->getGroup();
        $listOption = $this->getConfig("listOptions.$group");
        
        return !isset($listOption['paginate']) || $listOption['paginate'];
    }

    /**
     * get paginator links
     * 
     * @return string
     */
    public function paginator()
    {
        if ($this->enablePaginate()) {
            return $this->getBody()->getPaginator()->links();
        }
        
        return '';
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