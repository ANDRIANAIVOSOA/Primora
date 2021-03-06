<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use FOS\RestBundle\View\View;

class CapteurControllerTest extends WebTestCase
{
    private $deviceId = 'B19F897EE644411E90E771C7064CA304';
    private $capteurId = '92f8a295-96d0-4fdc-b334-8da29c6d0612';
    private $accessCode = '911647f7-1ea3-4672-bf05-b89a319364ab';

        /**
     * @var Client
     */
    private $client = null;

    public function setUp()
    {
        $this->client = static::createClient();
    }

    /**
     * @group controller
     * @group testRetourneListeCapteurs
     * @group testCapteurController
     */
    public function testRetourneListeCapteurParDeviceId(): void
    {
        $this->client->request('GET', "/api/capteur/device/$this->deviceId");
        $response = $this->client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("application/json", $response->headers->get('content-type'));
        $data = json_decode($response->getContent());
        $this->assertAttributeSame(true, 'success', $data);    }

    /**
     * @group controller
     * @group testRetourneListeCapteurs
     * @group testCapteurController
     */
    public function testRetourneCapteurParId(): void
    {
        $this->client->request('GET', "/api/capteur/$this->capteurId");
        $response = $this->client->getResponse();
        $this->assertEquals("application/json", $response->headers->get('content-type'));
        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @group controller
     * @group testCapteurController
     */
    public function testRetourneListeCapteurParAccessCode(): void
    {
        $this->client->request('GET', "/api/capteur/ac/$this->accessCode");
        $response = $this->client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("application/json", $response->headers->get('content-type'));
        $data = json_decode($response->getContent());
        $this->assertAttributeSame(true, 'success', $data);
    }

    /**
     * @group controller
     * @group testCapteurController
     */
     
    public function testInsertionCapteur(): void
    {
        $client = static::createClient();
        $client->request(
            'POST',
            "/api/capteur/add",
            [
                'name' => 'PLUIE',
                'unite' => 'degre',
                'type' => 2003,
                'accessCode' => '911647f7-1ea3-4672-bf05-b89a319364ab',
                'iconFull' => 'test1',
                'iconClear' => 'test2'
            ]
        );
        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("application/json", $response->headers->get('content-type'));
        $data = json_decode($response->getContent());
        $this->assertAttributeSame(true, 'success', $data);
    }

    /**
     * @group controller
     * @group testCapteurController
     */
     
    public function testSuppressionCapteur(): void
    {
        $client = static::createClient();
        $client->request(
            'DELETE',
            "/api/capteur/5d1d4c21-b861-4b6f-ab11-bcecdbe75e2f"
        );
        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("application/json", $response->headers->get('content-type'));
        $data = json_decode($response->getContent());
        $this->assertAttributeSame(true, 'success', $data);
    }

    /**
     * @group controller
     * @group testCapteurController
     */
     
    public function testModifCapteur(): void
    {
        $client = static::createClient();
        $client->request(
            'PUT',
            "/api/capteur/93288779-e43f-453c-a116-87b350b76550",
            [
                'name' => 'HAFANANA',
                'unite' => 'degre',
                'type' => 2002,
                'accessCode' => '911647f7-1ea3-4672-bf05-b89a319364ab',
                'iconFull' => 'iVBORw0KGgoAAAANSUhEUgAAAEIAAACKCAYAAAAe75wxAAAKr2lDQ1BEaXNwbGF5AABIiZWWZ1CT6RbHz/u+6YWW0FvoTZAiEEAgdEIRpIONkFBCCTEkqNhQWVzBtSAiAuqKroAo2Ciy2LBgWwTsdUEWBXVdLNhQcz9wCXvv3Jk7e2aeeX/zn/P8zznP++UA0G7xxOJsVAUgRySVRAX5shISk1jEp0AGPJDAENg8fp7YJzIyDABg6vu3QAA+3AEEAOCmLU8szoZ/FqqC1Dw+ABIJACmCPH4OAHICAOnkiyVSAEwMACZLpGIpAFYOAExJQmISAFYPAMz0Se4EAGbKJPcCAFMSE+UHgP0BQKLxeJJ0AOoYALDy+elSAJoKANiLBEIRAI0DAF78DJ4AgFYIADNycnIFALRDAGCZ8jef9P/wTFF48njpCp6cBQAASP7CPHE2b9k/fI7/HznZsqkaJgBAy5AERwEACQA5kZUbqmBRypyIKRYKJnsCQE5kyIJjp5if55c0xQKef6jibvacsClOEwZyFT5SbswUS3KjFP6peQHRU8yTTNeSZcX6KOqmchWeBRkx8VOcL4ybM8V5WdGh0zl+Cl0ii1L0nCYJVMyYk/e3uYRcRT6fN92PNCMmeLrPBEUPglT/AIUuilXki6W+Cn9xdqQiPzU7SKHn5Ucr7kolMdP50kjF+2TyQiKnGIQQDjzgS1OXSgEA/HLFyyTC9Awpy0cszk5lcUV8uxksR3sHNkBCYhJr8veO3QAEABBt1WltfR2Ad7FcLm+Z1oI+ABxdBEA1nNYs3ACUdQEuJ/BlkvxJDQcAgAcKKAMTtMEATMASbMERXMADOBAAIRABMZAIC4EPGZADElgCK2ANFEMpbIHtUAV7YB/Uw2E4Bm3QCefgElyDXrgND2EAhuEljMEHmEAQhIjQEQaijRgiZogN4oiwES8kAAlDopBEJBlJR0SIDFmBrENKkTKkCtmLNCBHkZPIOeQK0ofcRwaRUeQt8gXFUBrKRPVRc3QmykZ90FA0Bl2ApqOL0QK0CN2EVqK16CG0FT2HXkNvowPoS3QcA4yKaWBGmC3GxvywCCwJS8Mk2CqsBKvAarEmrAPrxm5iA9gr7DOOgGPgWDhbnAcuGBeL4+MW41bhNuKqcPW4VtwF3E3cIG4M9x1Px+vhbfDueC4+AZ+OX4IvxlfgD+Bb8Bfxt/HD+A8EAkGDYEFwJQQTEgmZhOWEjYRdhGbCWUIfYYgwTiQStYk2RE9iBJFHlBKLiTuJh4hniP3EYeInEpVkSHIkBZKSSCLSWlIF6SDpNKmf9Jw0QVYhm5HdyRFkAXkZeTN5P7mDfIM8TJ6gqFIsKJ6UGEomZQ2lktJEuUh5RHlHpVKNqW7UuVQhtZBaST1CvUwdpH6mqdGsaX60+TQZbROtjnaWdp/2jk6nm9M59CS6lL6J3kA/T39C/6TEULJT4ioJlFYrVSu1KvUrvVYmK5sp+ygvVC5QrlA+rnxD+ZUKWcVcxU+Fp7JKpVrlpMpdlXFVhqqDaoRqjupG1YOqV1RH1Ihq5moBagK1IrV9aufVhhgYw4Thx+Az1jH2My4yhpkEpgWTy8xkljIPM3uYY+pq6rPU49SXqlern1If0MA0zDW4GtkamzWOadzR+KKpr+mjmaq5QbNJs1/zo5auFkcrVatEq1nrttYXbZZ2gHaW9lbtNu3HOjgda525Okt0dutc1Hmly9T10OXrluge032gh+pZ60XpLdfbp3ddb1zfQD9IX6y/U/+8/isDDQOOQaZBucFpg1FDhqGXodCw3PCM4QuWOsuHlc2qZF1gjRnpGQUbyYz2GvUYTRhbGMcarzVuNn5sQjFhm6SZlJt0mYyZGpqGm64wbTR9YEY2Y5tlmO0w6zb7aG5hHm++3rzNfMRCy4JrUWDRaPHIkm7pbbnYstbylhXBim2VZbXLqtcatXa2zrCutr5hg9q42Ahtdtn0zcDPcJshmlE7464tzdbHNt+20XbQTsMuzG6tXZvd65mmM5Nmbp3ZPfO7vbN9tv1++4cOag4hDmsdOhzeOlo78h2rHW850Z0CnVY7tTu9mWUzK3XW7ln3nBnO4c7rnbucv7m4ukhcmlxGXU1dk11rXO+ymexI9kb2ZTe8m6/bardOt8/uLu5S92Puf3nYemR5HPQYmW0xO3X2/tlDnsaePM+9ngNeLK9kr5+9BryNvHnetd5POSYcAecA57mPlU+mzyGf1772vhLfFt+Pfu5+K/3O+mP+Qf4l/j0BagGxAVUBTwKNA9MDGwPHgpyDlgedDcYHhwZvDb7L1efyuQ3csRDXkJUhF0JpodGhVaFPw6zDJGEd4Wh4SPi28EdzzOaI5rRFQAQ3YlvE40iLyMWRv84lzI2cWz33WZRD1Iqo7mhG9KLog9EfYnxjNsc8jLWMlcV2xSnHzY9riPsY7x9fFj+QMDNhZcK1RJ1EYWJ7EjEpLulA0vi8gHnb5w3Pd55fPP/OAosFSxdcWaizMHvhqUXKi3iLjifjk+OTDyZ/5UXwannjKdyUmpQxvh9/B/+lgCMoF4ymeqaWpT5P80wrSxtJ90zflj6a4Z1RkfFK6CesEr7JDM7ck/kxKyKrLkueHZ/dnEPKSc45KVITZYku5BrkLs3tE9uIi8UDi90Xb188JgmVHMhD8hbktUuZUrH0usxS9oNsMN8rvzr/05K4JceXqi4VLb2+zHrZhmXPCwILflmOW85f3rXCaMWaFYMrfVbuXYWsSlnVtdpkddHq4cKgwvo1lDVZa35ba7+2bO37dfHrOor0iwqLhn4I+qGxWKlYUnx3vcf6PT/ifhT+2LPBacPODd9LBCVXS+1LK0q/buRvvPqTw0+VP8k3pW3q2eyyefcWwhbRljtbvbfWl6mWFZQNbQvf1lrOKi8pf7990fYrFbMq9uyg7JDtGKgMq2zfabpzy86vVRlVt6t9q5tr9Go21HzcJdjVv5uzu2mP/p7SPV9+Fv58b2/Q3tZa89qKfYR9+fue7Y/b3/0L+5eGAzoHSg98qxPVDdRH1V9ocG1oOKh3cHMj2ihrHD00/1DvYf/D7U22TXubNZpLj8AR2ZEXR5OP3jkWeqzrOPt40wmzEzUtjJaSVqR1WetYW0bbQHtie9/JkJNdHR4dLb/a/VrXadRZfUr91ObTlNNFp+VnCs6MnxWffXUu/dxQ16Kuh+cTzt+6MPdCz8XQi5cvBV463+3Tfeay5+XOK+5XTl5lX2275nKt9brz9ZbfnH9r6XHpab3heqO91623o2923+l+7/5zN/1vXrrFvXXt9pzbfXdi79y7O//uwD3BvZH72fffPMh/MPGw8BH+UcljlccVT/Se1P5u9XvzgMvAqUH/wetPo58+HOIPvfwj74+vw0XP6M8qnhs+bxhxHOkcDRztfTHvxfBL8cuJV8V/qv5Z89ry9Ym/OH9dH0sYG34jeSN/u/Gd9ru697Ped41Hjj/5kPNh4mPJJ+1P9Z/Zn7u/xH95PrHkK/Fr5Terbx3fQ78/kufI5WKehAcAABgAoGlpAG/rAOiJAIxeAMq8yf0YAACQyZ0eYHIH+d88uUMDAIALQBMARAEApxDgOAfAHACUACCCAxDDAdTJSXH+HXlpTo6TXkqNAEQjufxtLgA5F+BrkFw+ESmXf6sBwG4BnB6Z3MsBAAgqAE26aTzNBTf7O2Lgv+JfeOIGyTMoZ1wAAAAJcEhZcwAALiMAAC4jAXilP3YAAAZeaVRYdFhNTDpjb20uYWRvYmUueG1wAAAAAAA8P3hwYWNrZXQgYmVnaW49Iu+7vyIgaWQ9Ilc1TTBNcENlaGlIenJlU3pOVGN6a2M5ZCI/PiA8eDp4bXBtZXRhIHhtbG5zOng9ImFkb2JlOm5zOm1ldGEvIiB4OnhtcHRrPSJBZG9iZSBYTVAgQ29yZSA1LjYtYzE0MCA3OS4xNjA0NTEsIDIwMTcvMDUvMDYtMDE6MDg6MjEgICAgICAgICI+IDxyZGY6UkRGIHhtbG5zOnJkZj0iaHR0cDovL3d3dy53My5vcmcvMTk5OS8wMi8yMi1yZGYtc3ludGF4LW5zIyI+IDxyZGY6RGVzY3JpcHRpb24gcmRmOmFib3V0PSIiIHhtbG5zOnhtcD0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wLyIgeG1sbnM6ZGM9Imh0dHA6Ly9wdXJsLm9yZy9kYy9lbGVtZW50cy8xLjEvIiB4bWxuczpwaG90b3Nob3A9Imh0dHA6Ly9ucy5hZG9iZS5jb20vcGhvdG9zaG9wLzEuMC8iIHhtbG5zOnhtcE1NPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvbW0vIiB4bWxuczpzdEV2dD0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL3NUeXBlL1Jlc291cmNlRXZlbnQjIiB4bXA6Q3JlYXRvclRvb2w9IkFkb2JlIFBob3Rvc2hvcCBDQyAyMDE4IChNYWNpbnRvc2gpIiB4bXA6Q3JlYXRlRGF0ZT0iMjAxOC0xMS0xOVQxMDozNTowOSswMzowMCIgeG1wOk1vZGlmeURhdGU9IjIwMTgtMTEtMjBUMDg6NTk6MDIrMDM6MDAiIHhtcDpNZXRhZGF0YURhdGU9IjIwMTgtMTEtMjBUMDg6NTk6MDIrMDM6MDAiIGRjOmZvcm1hdD0iaW1hZ2UvcG5nIiBwaG90b3Nob3A6Q29sb3JNb2RlPSIzIiBwaG90b3Nob3A6SUNDUHJvZmlsZT0iRGlzcGxheSIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDpmZjczNGYwOS02MjEwLTQ0YzMtYTMxZi00OTRkMzJjNDQ4NWMiIHhtcE1NOkRvY3VtZW50SUQ9ImFkb2JlOmRvY2lkOnBob3Rvc2hvcDpjOWI4OWY2NC0zMTZlLWU4NDItODIyNy1iYzhmMTNiODVhN2IiIHhtcE1NOk9yaWdpbmFsRG9jdW1lbnRJRD0ieG1wLmRpZDoyZGE0NzFiZS04NzliLTRhMDItYmVkZi00ZjllMjgyMjhlYzEiPiA8eG1wTU06SGlzdG9yeT4gPHJkZjpTZXE+IDxyZGY6bGkgc3RFdnQ6YWN0aW9uPSJjcmVhdGVkIiBzdEV2dDppbnN0YW5jZUlEPSJ4bXAuaWlkOjJkYTQ3MWJlLTg3OWItNGEwMi1iZWRmLTRmOWUyODIyOGVjMSIgc3RFdnQ6d2hlbj0iMjAxOC0xMS0xOVQxMDozNTowOSswMzowMCIgc3RFdnQ6c29mdHdhcmVBZ2VudD0iQWRvYmUgUGhvdG9zaG9wIENDIDIwMTggKE1hY2ludG9zaCkiLz4gPHJkZjpsaSBzdEV2dDphY3Rpb249ImNvbnZlcnRlZCIgc3RFdnQ6cGFyYW1ldGVycz0iZnJvbSBhcHBsaWNhdGlvbi92bmQuYWRvYmUucGhvdG9zaG9wIHRvIGltYWdlL3BuZyIvPiA8cmRmOmxpIHN0RXZ0OmFjdGlvbj0ic2F2ZWQiIHN0RXZ0Omluc3RhbmNlSUQ9InhtcC5paWQ6ZmY3MzRmMDktNjIxMC00NGMzLWEzMWYtNDk0ZDMyYzQ0ODVjIiBzdEV2dDp3aGVuPSIyMDE4LTExLTIwVDA4OjU5OjAyKzAzOjAwIiBzdEV2dDpzb2Z0d2FyZUFnZW50PSJBZG9iZSBQaG90b3Nob3AgQ0MgMjAxOCAoTWFjaW50b3NoKSIgc3RFdnQ6Y2hhbmdlZD0iLyIvPiA8L3JkZjpTZXE+IDwveG1wTU06SGlzdG9yeT4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz7zcBOZAAAHd0lEQVR4nO2d3W3bSBSFjxd55AMDCHqNWMFSFUSqIHIFK1UQpQLHFcSpQEoFViqIUoG4FZD7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',
                'iconClear' => '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HACUACCCAxDDAdTJSXH+HXlpTo6TXkqNAEQjufxtLgA5F+BrkFw+ESmXf6sBwG4BnB6Z3MsBAAgqAE26aTzNBTf7O2Lgv+JfeOIGyTMoZ1wAAAAJcEhZcwAALiMAAC4jAXilP3YAAAZeaVRYdFhNTDpjb20uYWRvYmUueG1wAAAAAAA8P3hwYWNrZXQgYmVnaW49Iu+7vyIgaWQ9Ilc1TTBNcENlaGlIenJlU3pOVGN6a2M5ZCI/PiA8eDp4bXBtZXRhIHhtbG5zOng9ImFkb2JlOm5zOm1ldGEvIiB4OnhtcHRrPSJBZG9iZSBYTVAgQ29yZSA1LjYtYzE0MCA3OS4xNjA0NTEsIDIwMTcvMDUvMDYtMDE6MDg6MjEgICAgICAgICI+IDxyZGY6UkRGIHhtbG5zOnJkZj0iaHR0cDovL3d3dy53My5vcmcvMTk5OS8wMi8yMi1yZGYtc3ludGF4LW5zIyI+IDxyZGY6RGVzY3JpcHRpb24gcmRmOmFib3V0PSIiIHhtbG5zOnhtcD0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wLyIgeG1sbnM6ZGM9Imh0dHA6Ly9wdXJsLm9yZy9kYy9lbGVtZW50cy8xLjEvIiB4bWxuczpwaG90b3Nob3A9Imh0dHA6Ly9ucy5hZG9iZS5jb20vcGhvdG9zaG9wLzEuMC8iIHhtbG5zOnhtcE1NPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvbW0vIiB4bWxuczpzdEV2dD0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL3NUeXBlL1Jlc291cmNlRXZlbnQjIiB4bXA6Q3JlYXRvclRvb2w9IkFkb2JlIFBob3Rvc2hvcCBDQyAyMDE4IChNYWNpbnRvc2gpIiB4bXA6Q3JlYXRlRGF0ZT0iMjAxOC0xMS0xOVQxMDozNTowOSswMzowMCIgeG1wOk1vZGlmeURhdGU9IjIwMTgtMTEtMjBUMDg6NTk6MDIrMDM6MDAiIHhtcDpNZXRhZGF0YURhdGU9IjIwMTgtMTEtMjBUMDg6NTk6MDIrMDM6MDAiIGRjOmZvcm1hdD0iaW1hZ2UvcG5nIiBwaG90b3Nob3A6Q29sb3JNb2RlPSIzIiBwaG90b3Nob3A6SUNDUHJvZmlsZT0iRGlzcGxheSIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDpmZjczNGYwOS02MjEwLTQ0YzMtYTMxZi00OTRkMzJjNDQ4NWMiIHhtcE1NOkRvY3VtZW50SUQ9ImFkb2JlOmRvY2lkOnBob3Rvc2hvcDpjOWI4OWY2NC0zMTZlLWU4NDItODIyNy1iYzhmMTNiODVhN2IiIHhtcE1NOk9yaWdpbmFsRG9jdW1lbnRJRD0ieG1wLmRpZDoyZGE0NzFiZS04NzliLTRhMDItYmVkZi00ZjllMjgyMjhlYzEiPiA8eG1wTU06SGlzdG9yeT4gPHJkZjpTZXE+IDxyZGY6bGkgc3RFdnQ6YWN0aW9uPSJjcmVhdGVkIiBzdEV2dDppbnN0YW5jZUlEPSJ4bXAuaWlkOjJkYTQ3MWJlLTg3OWItNGEwMi1iZWRmLTRmOWUyODIyOGVjMSIgc3RFdnQ6d2hlbj0iMjAxOC0xMS0xOVQxMDozNTowOSswMzowMCIgc3RFdnQ6c29mdHdhcmVBZ2VudD0iQWRvYmUgUGhvdG9zaG9wIENDIDIwMTggKE1hY2ludG9zaCkiLz4gPHJkZjpsaSBzdEV2dDphY3Rpb249ImNvbnZlcnRlZCIgc3RFdnQ6cGFyYW1ldGVycz0iZnJvbSBhcHBsaWNhdGlvbi92bmQuYWRvYmUucGhvdG9zaG9wIHRvIGltYWdlL3BuZyIvPiA8cmRmOmxpIHN0RXZ0OmFjdGlvbj0ic2F2ZWQiIHN0RXZ0Omluc3RhbmNlSUQ9InhtcC5paWQ6ZmY3MzRmMDktNjIxMC00NGMzLWEzMWYtNDk0ZDMyYzQ0ODVjIiBzdEV2dDp3aGVuPSIyMDE4LTExLTIwVDA4OjU5OjAyKzAzOjAwIiBzdEV2dDpzb2Z0d2FyZUFnZW50PSJBZG9iZSBQaG90b3Nob3AgQ0MgMjAxOCAoTWFjaW50b3NoKSIgc3RFdnQ6Y2hhbmdlZD0iLyIvPiA8L3JkZjpTZXE+IDwveG1wTU06SGlzdG9yeT4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz7zcBOZAAAHd0lEQVR4nO2d3W3bSBSFjxd55AMDCHqNWMFSFUSqIHIFK1UQpQLHFcSpQEoFViqIUoG4FZD7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'
            ]
        );
        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("application/json", $response->headers->get('content-type'));
    }    
}
