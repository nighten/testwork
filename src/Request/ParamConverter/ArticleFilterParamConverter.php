<?php

namespace App\Request\ParamConverter;

use App\Repository\CategoryRepository;
use App\Entity\Filter\ArticleFilter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleFilterParamConverter implements ParamConverterInterface
{
    /** @var CategoryRepository */
    private CategoryRepository $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function apply(Request $request, ParamConverter $configuration): bool
    {
        $filter = new ArticleFilter();
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);

        try {
            $options = $resolver->resolve($request->query->all());
        } catch (InvalidOptionsException | \TypeError $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        $filter->setText($options['text']);
        if ($options['categories'] !== null) {
            $filter->initCategories();
        }
        foreach ($this->categoryRepository->findBy(['id' => $options['categories']]) as $category) {
            $filter->addCategory($category);
        }
        $filter->setActive($options['active']);
        $request->attributes->set($configuration->getName(), $filter);
        return true;
    }

    private function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'categories' => null,
            'text' => null,
            'active' => null,
        ]);
        $resolver->setNormalizer('active', fn(Options $options, $value) => $value === null ? null : $value === 'true');
    }

    public function supports(ParamConverter $configuration): bool
    {
        return $configuration->getClass() === ArticleFilter::class;
    }
}
