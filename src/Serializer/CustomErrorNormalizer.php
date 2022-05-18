<?php
namespace App\Serializer;

use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class CustomErrorNormalizer implements NormalizerInterface {

    public function normalize(mixed $object, ?string $format = null, array $context = []): string
    {
        header('content-type: application/json');
        return json_encode([
            "content" => "Contenu de l'erreur",
            'exception' => [
                'code' => $object->getStatusCode(),
                'message' => $object->getMessage()
            ]
            ]);
    }

    public function supportsNormalization(mixed $data, ?string $format = null)
    {
        return $data instanceof FlattenException;
    }
}