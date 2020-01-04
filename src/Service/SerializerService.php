<?php

namespace App\Service;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class SerializerService
{
    /** @var EntityManagerInterface  */
    private $entityManager;

    /**
     * SerializerService constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param $data
     * @param string|null $format
     * @param array $context
     * @return array|\ArrayObject|bool|float|int|mixed|string|null
     * @throws \Doctrine\Common\Annotations\AnnotationException
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function normalize($data, string $format = null, array $context = [])
    {
        $serializer = $this->getSerializer();

        return $serializer->normalize($data, $format, $context);
    }

    /**
     * @param $data
     * @param string $type
     * @param string|null $format
     * @param array $context
     * @return array|object
     * @throws \Doctrine\Common\Annotations\AnnotationException
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        $serializer = $this->getSerializer($type);

        return $serializer->denormalize($data, $type, $format, $context);
    }

    /**
     * @param $data
     * @param string $format
     * @param array $context
     * @return string
     * @throws \Doctrine\Common\Annotations\AnnotationException
     */
    public function serialize($data, string $format, array $context = []): string
    {
        $serializer = $this->getSerializer();

        return $serializer->serialize($data, $format, $context);
    }

    /**
     * @param $data
     * @param string $type
     * @param string $format
     * @param array $context
     * @return array|object
     * @throws \Doctrine\Common\Annotations\AnnotationException
     */
    public function deserialize($data, string $type, string $format, array $context = [])
    {
        $serializer = $this->getSerializer($type);

        return $serializer->deserialize($data, $type, $format, $context);
    }

    /**
     * @param string|null $type
     * @return Serializer
     * @throws \Doctrine\Common\Annotations\AnnotationException
     */
    private function getSerializer(?string $type = null): Serializer
    {
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));

        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer($classMetadataFactory)];

        if ($type) {
            $normalizerNamespace = str_replace('Entity', 'Normalizer', $type) . 'Normalizer';

            if (class_exists($normalizerNamespace)) {
                $normalizers = [new $normalizerNamespace($this->entityManager, $classMetadataFactory)];
            }
        }

        return new Serializer($normalizers, $encoders);
    }
}
