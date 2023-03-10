@include('core.table::partials.modal-item', [
    'type' => 'danger',
    'name' => 'modal-confirm-delete',
    'title' => trans('core/base::tables.confirm_delete'),
    'content' => trans('core/base::tables.confirm_delete_msg'),
    'action_name' => trans('core/base::tables.delete'),
    'action_button_attributes' => [
        'class' => 'delete-crud-entry',
    ],
])

@include('core.table::partials.modal-item', [
    'type' => 'danger',
    'name' => 'delete-many-modal',
    'title' => trans('core/base::tables.confirm_delete'),
    'content' => trans('core/base::tables.confirm_delete_many_msg'),
    'action_name' => trans('core/base::tables.delete'),
    'action_button_attributes' => [
        'class' => 'delete-many-entry-button',
    ],
])

@include('core.table::partials.modal-item', [
    'type' => 'info',
    'name' => 'modal-bulk-change-items',
    'title' => trans('core/base::tables.bulk_changes'),
    'content' => '<div class="modal-bulk-change-content"></div>',
    'action_name' => trans('core/base::tables.submit'),
    'action_button_attributes' => [
        'class' => 'confirm-bulk-change-button',
        'data-load-url' => route('tables.bulk-change.data'),
    ],
])

@include('core.table::partials.modal-item', [
    'type' => 'primary',
    'name' => 'modal-confirm-register-main-content',
    'title' => __('contents.register_main_content_title'),
    'content' => __('contents.register_main_content_content'),
    'action_name' => __('contents.main_content.button.confirm'),
    'action_button_attributes' => [
        'class' => 'register-main-content',
    ],
])

@include('core.table::partials.modal-item', [
    'type' => 'primary',
    'name' => 'modal-confirm-un-register-main-content',
    'title' => __('contents.register_main_content_title'),
    'content' => __('contents.un_register_main_content_content'),
    'action_name' => __('contents.main_content.button.confirm'),
    'action_button_attributes' => [
        'class' => 'register-main-content',
    ],
])