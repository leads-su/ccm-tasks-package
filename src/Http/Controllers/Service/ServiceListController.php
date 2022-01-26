<?php

namespace ConsulConfigManager\Tasks\Http\Controllers\Service;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;
use ConsulConfigManager\Domain\ViewModels\HttpResponseViewModel;
use ConsulConfigManager\Tasks\UseCases\Service\List\ServiceListInputPort;
use ConsulConfigManager\Tasks\UseCases\Service\List\ServiceListRequestModel;

/**
 * Class ServiceListController
 * @package ConsulConfigManager\Tasks\Http\Controllers\Service
 */
class ServiceListController extends Controller
{
    /**
     * Input port interactor instance
     * @var ServiceListInputPort
     */
    private ServiceListInputPort $interactor;

    /**
     * ServiceListController constructor.
     * @param ServiceListInputPort $interactor
     * @return void
     */
    public function __construct(ServiceListInputPort $interactor)
    {
        $this->interactor = $interactor;
    }

    // @codeCoverageIgnoreStart

    /**
     * Handle incoming request
     * @param Request $request
     * @return Response|null
     */
    public function __invoke(Request $request): ?Response
    {
        $viewModel = $this->interactor->list(
            new ServiceListRequestModel($request)
        );

        if ($viewModel instanceof HttpResponseViewModel) {
            return $viewModel->getResponse();
        }

        return null;
    }


    // @codeCoverageIgnoreEnd
}
