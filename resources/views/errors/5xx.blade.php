@include('errors.layout', ['code' => $exception->getStatusCode(), 'icon' => 'exclamation-circle', 'title' => __('errors.title'), 'message' => __('errors.message')])
