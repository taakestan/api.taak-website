<?php

namespace App\Tools;

use App\Exceptions\InvalidBase64Data;

trait Base64Generator {

    /**
     * create string file
     *
     * @param string $base64data
     * @return \Illuminate\Http\File
     * @throws InvalidBase64Data
     */
    public function createFileFromBase64(string $base64data)
    {
        if (!str_start($base64data, 'data:'))
            throw new InvalidBase64Data;


        // stripe out meme type exp -> (data:image/jpeg)
        [$_, $base64data] = explode(';', $base64data);

        // stripe out base64 data
        [$_, $base64data] = explode(',', $base64data);


        // strict mode filters for non-base64 alphabet characters
        if (base64_decode($base64data, true) === false) {
            throw new InvalidBase64Data;
        }

        // decoding and then encoding should not change the data
        if (base64_encode(base64_decode($base64data)) !== $base64data) {
            throw new InvalidBase64Data;
        }

        // change base64 data to binary data
        $binaryData = base64_decode($base64data);

        // temporarily store the decoded data on the filesystem to be able to pass it to the fileAdder
        $tmpFile = tempnam(sys_get_temp_dir(), 'medialibrary');

        file_put_contents($tmpFile, $binaryData);

        return new \Illuminate\Http\File($tmpFile);
    }
}