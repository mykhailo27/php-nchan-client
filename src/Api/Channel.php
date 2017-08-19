<?php

namespace Marein\Nchan\Api;

use Marein\Nchan\ChannelInformation;
use Marein\Nchan\Exception\NchanException;
use Marein\Nchan\Http\Client;
use Marein\Nchan\Http\Request;
use Marein\Nchan\Http\Response;
use Marein\Nchan\Http\Url;
use Marein\Nchan\Message;

final class Channel
{
    /**
     * @var Url
     */
    private $channelUrl;

    /**
     * @var Client
     */
    private $client;

    /**
     * Channel constructor.
     *
     * @param Url    $channelUrl
     * @param Client $client
     */
    public function __construct(Url $channelUrl, Client $client)
    {
        $this->channelUrl = $channelUrl;
        $this->client = $client;
    }

    /**
     * Publish the message in the given channel to nchan.
     *
     * @param Message $message
     *
     * @return ChannelInformation
     * @throws NchanException
     */
    public function publish(Message $message): ChannelInformation
    {
        $response = $this->client->post(new Request(
            $this->channelUrl,
            [
                'Accept'              => 'application/json',
                'Content-Type'        => 'application/x-www-form-urlencoded',
                'X-EventSource-Event' => $message->name()
            ],
            $message->payload()
        ));

        if (in_array($response->statusCode(), [Response::CREATED, Response::ACCEPTED])) {
            return ChannelInformation::fromJson($response->body());
        }

        throw new NchanException(
            'Unable to publish to channel. Maybe the channel does not exists.'
        );
    }

    /**
     * @return ChannelInformation
     * @throws NchanException
     */
    public function information(): ChannelInformation
    {
        $response = $this->client->get(new Request(
            $this->channelUrl,
            [
                'Accept' => 'application/json'
            ]
        ));

        if ($response->statusCode() == Response::OK) {
            return ChannelInformation::fromJson($response->body());
        }

        if ($response->statusCode() == Response::NOT_FOUND) {
            return new ChannelInformation(0, 0, 0, '');
        }

        throw new NchanException(
            'Unable to get channel information. Maybe the channel does not exists.'
        );
    }

    /**
     * @return bool
     * @throws NchanException
     */
    public function delete(): bool
    {
        $response = $this->client->delete(new Request(
            $this->channelUrl,
            [
                'Accept' => 'application/json'
            ]
        ));

        if (in_array($response->statusCode(), [Response::OK, Response::NOT_FOUND])) {
            return true;
        }

        throw new NchanException(
            'Unable to delete channel. Maybe the channel does not exists.'
        );
    }
}