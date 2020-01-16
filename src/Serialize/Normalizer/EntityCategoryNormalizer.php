<?php


namespace App\Serialize\Normalizer;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class EntityCategoryNormalizer extends ObjectNormalizer
{
    /**
     * @var CategoryRepository
     */
    private CategoryRepository $categoryRepository;

    /**
     * @required
     * @param CategoryRepository $categoryRepository
     */
    public function injectEntityManager(CategoryRepository $categoryRepository): void
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function supportsDenormalization($data, string $type, string $format = null): bool
    {
        return $type === Category::class && is_numeric($data);
    }

    /**
     * @inheritDoc
     */
    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        return $this->categoryRepository->find($data);
    }
}
