<?php


namespace SmallRuralDog\LaravelCustom\Api;


use Exception;
use Illuminate\Http\Request;

class ExceptionReport
{
    use ApiResponse;
    /**
     * @var Exception
     */
    public $exception;
    /**
     * @var Request
     */
    public $request;

    /**
     * @var
     */
    protected $report;
    /**
     * @var array
     */
    public $doReport = [];

    /**
     * ExceptionReport constructor.
     * @param Request $request
     * @param Exception $exception
     */
    function __construct(Request $request, Exception $exception)
    {
        $this->request = $request;
        $this->exception = $exception;
        $this->doReport = array_merge(config('laravel-custom.do-report'), [
            \Symfony\Component\HttpKernel\Exception\HttpException::class => [$exception->getMessage(), 400]
        ]);
    }

    /**
     * @return bool
     */
    public function shouldReturn()
    {
        if (!($this->request->wantsJson() || $this->request->ajax())) {
            return false;
        }
        foreach (array_keys($this->doReport) as $report) {
            if ($this->exception instanceof $report) {
                $this->report = $report;
                return true;
            }
        }
        return true;

    }

    /**
     * @param Exception $e
     * @return static
     */
    public static function make(Exception $e)
    {
        return new static(\request(), $e);
    }

    /**
     * @return mixed
     */
    public function report()
    {
        try {
            if ($this->report) {
                $message = $this->doReport[$this->report];
                if (!$message[0]) {
                    $message_str = $this->exception->getMessage();
                } else {
                    $message_str = $message[0] ?? 'error';
                }
                $code = $this->exception->getCode();
                return $this->failed($message_str, $message[1] ?? $code, $message[2] ?? 'error');
            } else {
                return $this->failed('系统异常');
            }
        } catch (Exception $exception) {
            return $this->failed('系统错误:' . $exception->getMessage());
        }


    }


}