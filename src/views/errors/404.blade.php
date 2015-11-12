<h1>
    {{ $exception->getStatusCode() }}
</h1>

<p>
    @if(!empty($exception->getMessage()))
        {{ $exception->getMessage() }}
    @else
        {{ \Symfony\Component\HttpFoundation\Response::$statusTexts[$exception->getStatusCode()] }}
    @endif
</p>