<?php

namespace Mantiq\Actions\Slack\Messages;

use Mantiq\Exceptions\ErrorException;
use Mantiq\Models\Action;
use Mantiq\Models\ActionInvocationContext;
use Mantiq\Models\DataType;
use Mantiq\Models\OutputDefinition;
use SlackIntegrationWithMantiq;

class SendMessageToSlackChannel extends Action
{
    public function getName()
    {
        return __('Send message to Slack channel', 'mantiq');
    }

    public function getDescription()
    {
        return __('Send a message to a specific Slack channel.', 'mantiq');
    }

    public function getGroup()
    {
        return __('Slack', 'mantiq');
    }

    public function getOutputs()
    {
        return [
            new OutputDefinition(
                [
                    'id'          => 'success',
                    'name'        => __('Operation state', 'mantiq'),
                    'description' => __('Whether the operation succeeded or not.', 'mantiq'),
                    'type'        => DataType::boolean(),
                ]
            ),
        ];
    }

    public function getTemplate()
    {
        return SlackIntegrationWithMantiq::getPath('views/actions/messages/send-message-to-slack-channel.php');
    }

    public function invoke(ActionInvocationContext $invocation)
    {
        $customArguments = json_decode($invocation->getEvaluatedArgument('customArguments', '{}'), true);
        $payload         = json_decode($invocation->getEvaluatedArgument('payload', '{}'), true);
        $type            = trim((string) $invocation->getArgument('type', 'markdown'));

        $userArguments = array_merge(
            [
                'webhook_url' => trim((string) $invocation->getEvaluatedArgumentWithoutEscaping('webhook_url', '')),
                'icon_emoji'  => trim((string) $invocation->getEvaluatedArgumentWithoutEscaping('icon_emoji', '')),
                'content'     => trim((string) $invocation->getEvaluatedArgumentWithoutEscaping('content', '')),
                'blocks'      => [],
            ],
            $type === 'payload' ? $payload : [],
            $customArguments ?: []
        );

        if (!empty($userArguments['content']) && $type === 'markdown') {
            array_unshift(
                $userArguments['blocks'],
                [
                    'type' => 'section',
                    'text' => [
                        'type' => 'mrkdwn',
                        'text' => $userArguments['content'],
                    ],
                ]
            );
        }

        unset($userArguments['content']);

        $request = wp_remote_post($userArguments['webhook_url'], [
            'headers'     => ['Content-Type' => 'application/json; charset=utf-8'],
            'body'        => json_encode($userArguments),
            'method'      => 'POST',
            'data_format' => 'body',
        ]);

        if ($request instanceof \WP_Error) {
            return [
                'success' => false,
                'error'   => new ErrorException($request->get_error_message()),
            ];
        }

        $response = wp_remote_retrieve_body($request);

        if ($response instanceof \WP_Error) {
            return [
                'success' => false,
                'error'   => new ErrorException($response->get_error_message()),
            ];
        }

        return [
            'success'  => $request['response']['code'] == 200,
            'response' => $request,
        ];
    }
}
