<?php

namespace Botble\AuditLog\Listeners;

use Botble\AuditLog\Events\AuditHandlerEvent;
use Botble\AuditLog\Repositories\Interfaces\AuditLogInterface;
use Illuminate\Http\Request;

class AuditHandlerListener
{
    /**
     * @var AuditLogInterface
     */
    public $auditLogRepository;

    /**
     * @var Request
     */
    protected $request;

    /**
     * AuditHandlerListener constructor.
     * @param AuditLogInterface $auditLogRepository
     * @param Request $request
     * @author Sang Nguyen
     */
    public function __construct(AuditLogInterface $auditLogRepository, Request $request)
    {
        $this->auditLogRepository = $auditLogRepository;
        $this->request = $request;
    }

    /**
     * Handle the event.
     *
     * @param  AuditHandlerEvent $event
     * @return void
     * @author Sang Nguyen
     */
    public function handle(AuditHandlerEvent $event)
    {
        $data = [
            'user_agent'     => $this->request->userAgent(),
            'ip_address'     => $this->request->ip(),
            'module'         => $event->module,
            'action'         => $event->action,
            'user_id'        => $this->request->user() ? $this->request->user()->getKey() : 0,
            'reference_user' => $event->referenceUser,
            'reference_id'   => $event->referenceId,
            'reference_name' => $event->referenceName,
            'type'           => $event->type,
        ];

        if (!in_array($event->action, ['loggedin', 'password'])) {
            $data['request'] = json_encode($this->request->input());
        }

        $this->auditLogRepository->createOrUpdate($data);
    }
}
