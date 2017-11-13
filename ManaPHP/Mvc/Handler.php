<?php

namespace ManaPHP\Mvc;

use ManaPHP\Component;
use ManaPHP\Mvc\Router\NotFoundRouteException;
use ManaPHP\Utility\Text;

/**
 * Class ManaPHP\Mvc\Handler
 *
 * @package ManaPHP\Mvc
 *
 * @property \ManaPHP\Mvc\ViewInterface      $view
 * @property \ManaPHP\Mvc\Dispatcher         $dispatcher
 * @property \ManaPHP\Mvc\RouterInterface    $router
 * @property \ManaPHP\Http\RequestInterface  $request
 * @property \ManaPHP\Http\ResponseInterface $response
 */
class Handler extends Component implements HandlerInterface
{
    /**
     * @var string
     */
    protected $_lastModule;

    /**
     * @var array
     */
    protected $_loadedModules = [];

    /**
     * Handles a MVC request
     *
     * @return \ManaPHP\Http\ResponseInterface
     * @throws \ManaPHP\Mvc\Action\NotFoundException
     * @throws \ManaPHP\Mvc\Action\Exception
     * @throws \ManaPHP\Mvc\Application\Exception
     * @throws \ManaPHP\Event\Exception
     * @throws \ManaPHP\Mvc\Application\NotFoundModuleException
     * @throws \ManaPHP\Mvc\Dispatcher\Exception
     * @throws \ManaPHP\Mvc\Dispatcher\NotFoundControllerException
     * @throws \ManaPHP\Mvc\Dispatcher\NotFoundActionException
     * @throws \ManaPHP\Mvc\View\Exception
     * @throws \ManaPHP\Renderer\Exception
     * @throws \ManaPHP\Alias\Exception
     * @throws \ManaPHP\Mvc\Router\Exception
     * @throws \ManaPHP\Mvc\Router\NotFoundRouteException
     */
    public function handle()
    {
        if (!$this->router->handle()) {
            throw new NotFoundRouteException('router does not have matched route for `:uri`'/**m0980aaf224562f1a4*/, ['uri' => $this->router->getRewriteUri()]);
        }

        $moduleName = $this->router->getModuleName();
        $controllerName = $this->router->getControllerName();
        $areaName = Text::camelize($this->router->getAreaName());
        $actionName = $this->router->getActionName();
        $params = $this->router->getParams();

        if ($this->_lastModule !== $moduleName) {
            $this->_lastModule = $moduleName;

            if ($areaName === null) {
                $this->alias->set('@module', "@app/$moduleName");
                $this->alias->set('@ns.module', '@ns.app\\' . $moduleName);
            } else {
                $this->alias->set('@module', "@app/$moduleName/Areas/$areaName");
                $this->alias->set('@ns.module', "@ns.app\\$moduleName\\Areas\\$areaName");
            }

            $this->alias->set('@views', '@module/Views');
            $this->alias->set('@messages', '@module/Messages');
        }

        if (!isset($this->_loadedModules[$moduleName])) {
            $moduleServiceName = lcfirst($moduleName) . 'Module';
            $moduleClassName = $this->alias->resolveNS('@ns.module\\Module');
            $this->_dependencyInjector->setShared($moduleServiceName, ['class' => class_exists($moduleClassName) ? $moduleClassName : 'ManaPHP\Mvc\Module', $moduleName]);
            /**
             * @var \ManaPHP\Mvc\ModuleInterface $moduleInstance
             */
            $moduleInstance = $this->_dependencyInjector->getShared($moduleServiceName);
            $moduleInstance->registerServices();
            $this->_loadedModules[$moduleName] = $moduleInstance;
        } else {
            $moduleInstance = $this->_loadedModules[$moduleName];
        }

        do {
            $r = $moduleInstance->antiCsrf();
            if ($r !== null && $r !== true) {
                break;
            }

            $r = $moduleInstance->authenticate();
            if ($r !== null && $r !== true) {
                break;
            }

            $r = $moduleInstance->authorize($this->dispatcher->getControllerName(), $this->dispatcher->getActionName());
            if ($r !== null && $r !== true) {
                break;
            }

            $ret = $this->dispatcher->dispatch($moduleName, $areaName, $controllerName, $actionName, $params);
            if ($ret !== false) {
                $actionReturnValue = $this->dispatcher->getReturnedValue();
                if ($actionReturnValue === null) {
                    $this->view->render($this->dispatcher->getControllerName(), $this->dispatcher->getActionName());
                    $this->response->setContent($this->view->getContent());
                }
            }
        } while (false);

        return $this->response;
    }
}