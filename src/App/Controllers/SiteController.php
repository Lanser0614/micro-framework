<?php
declare(strict_types=1);

namespace Lanser\MyFreamwork\App\Controllers;

use Lanser\MyFreamwork\Core\Attributes\Route;
use Lanser\MyFreamwork\Core\Controller\AbstractController;
use Lanser\MyFreamwork\Core\Database\Manager\EntityQueryBuilder;
use Lanser\MyFreamwork\Core\Database\Mapper\EntityMapper;

class SiteController extends AbstractController
{
    public function __construct(
        private readonly EntityQueryBuilder $entityQueryBuilder,
        private readonly EntityMapper       $entityMapper,
    )
    {
    }

    #[Route(route: '/api', method: 'GET')]
    public function index(): string
    {
        return 'Salom';
    }

    /**
     * @throws \ReflectionException
     * @throws \Exception
     */
    #[Route(route: '/test', method: 'GET')]
    public function test(): bool|string
    {
//        $data = $this->entityQueryBuilder->query('users')->where('id', '=', 1)->oneItem()->persist()->first();
//        $entity = $this->entityMapper->mapToEntityFill(User::class, $data);

//        return $this->jsonResponse($entity);
//
//        $data = $this->entityQueryBuilder->query('users')->where('id', '=', 1)->persist()->first();
//        /** @var User $entity */
//        $entity = $this->entityMapper->mapToEntityFill(User::class, $data);
//
//        $entity->setName('Salom');
//
//        $this->entityQueryBuilder->save($this->entityMapper->mapToDatabaseFill($entity))->andWhere('password', '=', 's')->persistSave();

        return $this->jsonResponse([
            "data" => 'Success'
        ]);
    }
}