<?php

namespace App\Service\Providers;

use App\Enum\Provider as ProviderEnum;
use App\Exception\AppraiserException;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ProviderFactory
{
    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly ManagerRegistry $doctrine,
        private readonly RequestStack $requestStack,
        private readonly LoggerInterface $logger,
    ) {
        //
    }

    /**
     * @throws AppraiserException
     */
    public function getProvider(): ProviderService
    {
        try {
            $request = $this->requestStack->getCurrentRequest();
            $providerName = ProviderEnum::from(strtolower($request->get('provider')));
            $providerClass = __NAMESPACE__ . '\\' . ucfirst($providerName->value) . 'ProviderService';

            /** @var ProviderService $provider */
             $provider = new $providerClass($this->client, $this->doctrine);
             $provider->setTerm($request->get('term'));

             return $provider;
        } catch (\Throwable $exception) {
            $this->logger->critical(
                sprintf(
                    'Exception in class [%s]. Message: "%s"',
                    __CLASS__,
                    $exception->getMessage()
                )
            );
            throw new AppraiserException();
        }
    }
}
