<?php

namespace App\Services;

class QuizzService
{
    public function generateQuizzData($categorie)
    {
        $prompt = "Please create a quizz with 5 questions in french about ". $categorie . " and be original please"        
        ."\nYou must give me the data following this exemple :"
   
        ."["
        ."\n    {"
        ."\n        \"question\": \"Quel temps fait il aujourd'hui ?\","
        ."\n        \"answers\": ["
        ."\n            \"Pluvieux\","
        ."\n            \"Ensoleille\","
        ."\n            \"Nuageux\","
        ."\n            \"Orageux\""
        ."\n        ],"
        ."\n        \"correct_answer\": 1"
        ."\n    },"
        ."\n    {"
        ."\n        \"question\": \"Quel temps fait il aujourd'hui ?\","
        ."\n        \"answers\": ["
        ."\n            \"Pluvieux\","
        ."\n            \"Ensoleille\","
        ."\n            \"Nuageux\","
        ."\n            \"Orageux\""
        ."\n        ],"
        ."\n        \"correct_answer\": 1"
        ."\n    },"
        ."\n    {"
        ."\n        \"question\": \"Quel temps fait il aujourd'hui ?\","
        ."\n        \"answers\": ["
        ."\n            \"Pluvieux\","
        ."\n            \"Ensoleille\","
        ."\n            \"Nuageux\","
        ."\n            \"Orageux\""
        ."\n        ],"
        ."\n        \"correct_answer\": 1"
        ."\n    }"
        ."\n]";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.openai.com/v1/completions');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        
        $headers = array(
            "Content-Type: application/json",
            "Authorization: Bearer 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"
        );
        
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        
        $data = array();
        $data["model"] = 'text-davinci-003';
        $data["prompt"] = $prompt;
        $data["temperature"] = 0.7;
        $data["max_tokens"] = 1000;

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $response = curl_exec($ch);
        $response = json_decode($response, true);

        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
        
        return json_decode($response["choices"][0]["text"]);
    }
}
?>