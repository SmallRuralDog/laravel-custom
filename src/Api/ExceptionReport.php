<?php
/**
 * User: ZhangWei
 * Date: 2018/5/15
 * Time: 15:47
 */

namespace SmallRuralDog\LaravelCustom\Api;


use Exception;
use Illuminate\Http\Request;
use SmallRuralDog\LaravelCustom\Exceptions\ApiExceptions;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

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
        $this->doReport = config('laravel-custom.do-report', [
            ApiExceptions::class => [false, 400],
            MethodNotAllowedHttpException::class=>['请求失败',400]
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
        return false;

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
        $message = $this->doReport[$this->report];

        if (!$message[0]) {
            $message_str = $this->exception->getMessage();
        } else {
            $message_str = $message[0] ?? 'error';
        }
        return $this->failed($message_str, $message[1] ?? 'error', $message[2] ?? 'error');

    }


}