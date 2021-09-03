<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class Response
{
    public $code    =   '';
    public $data    =   [];
    public $message =   'Ok';
    public $status  =   'success';

    public static function instance()
    {
        return new self();
    }

    public function withStatusCode(string $status_code_prefix, string $status_code_suffix)
    {
        $this->code = $this->getCode(strtolower($status_code_prefix), strtolower($status_code_suffix));

        return $this;
    }

    protected function getCode(string $prefix_path, string $suffix_path): int
    {
        $file           =   Storage::disk('local')->get('code.json');
        $contents       =   json_decode($file, true);
        $prefix         =   strval(data_get($contents, $prefix_path));
        $suffix         =   str_pad(data_get($contents, $suffix_path), 3, '0', STR_PAD_LEFT);

        return (int) $prefix . $suffix;
    }

    public function withMessage(string $message = 'Ok', bool $flash = false)
    {
        $this->message = $message;

        if ($flash) {

            request()->session()->flash($this->status, $this->message);
        }

        return $this;
    }

    public function withData(array $data = [])
    {
        if (!empty($data)) {

            array_walk_recursive($data, function (&$value, $key) {

                $value = is_null($value) ? "" : $value;
            });

            $this->data = $data;
        };

        return $this;
    }

    public function withStatus(string $status = 'fail')
    {
        $this->status = ($status == 'success') ? true : false;

        return $this;
    }

    public function sendJson(int $status_code = 200)
    {
        return response()->json($this->getResponse(), $status_code);
    }

    public function getResponse(): array
    {
        return [
            'status'    =>  $this->status,
            'code'      =>  $this->code,
            'message'   =>  $this->message,
            'data'      =>  $this->data
        ];
    }
}
