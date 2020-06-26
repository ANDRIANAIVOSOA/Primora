<?php

namespace App\Tests;

use App\Kernel;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Routing\RouteCollectionBuilder;

class KernelTest extends TestCase
{
    /**
     * @group kernel
     */
    public function testGetcachedirRetourneString(): void
    {
        $kernel = new Kernel('test', true);
        $cacheDir = $kernel->getCacheDir();
        $this->assertStringEndsWith('/var/cache/test', $cacheDir);
    }

    /**
     * @group kernel
     */
    public function testGetlogdirRetourneString(): void
    {
        $kernel = new Kernel('test', true);
        $logDir = $kernel->getLogDir();
        $this->assertStringEndsWith('/var/log', $logDir);
    }

    /**
     * @group kernel
     */
    public function testRegisterbundleRetourneGenerateur(): void
    {
        $kernel = new Kernel('test', true);
        $res = $kernel->registerBundles();
        $this->assertInstanceOf(\Generator::class, $res);
    }

    /**
     * @group kernel
     */
    public function testConfigurecontainerCallContainerEtLoader(): void
    {
        $container = $this->createMock(ContainerBuilder::class);
        $container->expects($this->once())
            ->method('addResource');
        $container->expects($this->exactly(2))
            ->method('setParameter');
        $loader = $this->createMock(LoaderInterface::class);
        $loader->expects($this->exactly(4))
            ->method('load');
        try {
            $class = new \ReflectionClass(Kernel::class);
            $method = $class->getMethod('configureContainer');
            $method->setAccessible(true);
            $kernel = new Kernel('test', true);
            $method->invokeArgs($kernel, [$container, $loader]);
        } catch (\ReflectionException $e) {
            $this->assertTrue(false, 'Kernel test error: ' . $e->getMessage());
        }
    }

    /**
     * @group kernel
     */
    public function testConfigureroute(): void
    {
        $routes = $this->createMock(RouteCollectionBuilder::class);
        $routes->expects($this->exactly(3))
            ->method('import');
        try {
            $class = new \ReflectionClass(Kernel::class);
            $method = $class->getMethod('configureRoutes');
            $method->setAccessible(true);
            $kernel = new Kernel('test', true);
            $method->invokeArgs($kernel, [$routes]);
        } catch (\ReflectionException $e) {
            $this->assertTrue(false, 'Kernel test error: ' . $e->getMessage());
        }
    }
}
