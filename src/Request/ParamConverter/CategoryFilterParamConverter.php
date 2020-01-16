<?php

namespace App\Request\ParamConverter;

use App\Entity\Filter\CategoryFilter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Options;

class CategoryFilterParamConverter implements ParamConverterInterface
{
    public function apply(Request $request, ParamConverter $configuration): bool
    {
        $filter = new CategoryFilter();
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);
        try {
            $options = $resolver->resolve($request->query->all());
        } catch (InvalidOptionsException | \TypeError $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        $filter->setActive($options['active']);
        $request->attributes->set($configuration->getName(), $filter);
        return true;
    }

    private function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'active' => null,
        ]);
        $resolver->setNormalizer('active', fn(Options $options, $value) => $value === null ? null : $value === 'true');
    }

    public function supports(ParamConverter $configuration): bool
    {
        return $configuration->getClass() === CategoryFilter::class;
    }
}
