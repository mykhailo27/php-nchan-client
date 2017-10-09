<?php

namespace Marein\Nchan\Http;

use Marein\Nchan\Exception\AuthenticationRequiredException;

class ThrowExceptionIfRequestRequiresAuthenticationClient implements Client
{
    /**
     * @var Client
     */
    private $client;

    /**
     * ThrowExceptionIfRequestRequiresAuthenticationClient constructor.
     *
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @inheritdoc
     */
    public function get(Request $request): Response
    {
        return $this->throwExceptionIfRequestRequiresAuthenticationOrReturnResponse(
            'GET',
            $request,
            $this->client->get($request)
        );
    }

    /**
     * @inheritdoc
     */
    public function post(Request $request): Response
    {
        return $this->throwExceptionIfRequestRequiresAuthenticationOrReturnResponse(
            'POST',
            $request,
            $this->client->post($request)
        );
    }

    /**
     * @inheritdoc
     */
    public function delete(Request $request): Response
    {
        return $this->throwExceptionIfRequestRequiresAuthenticationOrReturnResponse(
            'DELETE',
            $request,
            $this->client->delete($request)
        );
    }

    /**
     * Throws an exception if request requires authentication.
     *
     * @param string   $method
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws AuthenticationRequiredException
     */
    private function throwExceptionIfRequestRequiresAuthenticationOrReturnResponse(
        string $method,
        Request $request,
        Response $response
    ): Response {
        if ($response->statusCode() === Response::FORBIDDEN) {
            throw new AuthenticationRequiredException(
                sprintf(
                    'Request to "%s %s" requires authentication."',
                    $method,
                    $request->url()
                )
            );
        }

        return $response;
    }
}
