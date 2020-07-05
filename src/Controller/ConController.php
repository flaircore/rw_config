<?php


namespace Drupal\rw_config\Controller;


use Drupal\Component\Serialization\Json;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Controller\ControllerBase;
use GuzzleHttp\Client;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;

class ConController extends ControllerBase {


  /**
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * @var \GuzzleHttp\Client
   */
  private $client;

  public function __construct(ConfigFactoryInterface $configFactory, Client $client) {
    $this->configFactory = $configFactory;
    $this->client = $client;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      # see https://www.drupal.org/docs/drupal-apis/configuration-api/simple-configuration-api#s-interacting-with-configuration
      $container->get('config.factory'),
      # see  https://www.drupal.org/docs/contributed-modules/http-client-manager
      $container->get('http_client')
    );
  }

  public function showCats(){
    $config = $this->configFactory->getEditable('rw_config.settings');
    $api_url = 'https://api.thecatapi.com/v1/images/search?limit=20&page=1&order=Desc';

    $options = [
      'headers' => [
        'Content-Type' => 'application/json',
        'x-api-key' => $config->get('api_key')
      ]
    ];

    $data = $this->client->request('GET', $api_url, $options);

    $items = Json::decode($data->getBody()->getContents());

    #dump($config->get('api_key'));
    #dump($config->get('app_title'));
    #dump($config->get('show_pictures'));
    #dump($items);



    return [
      '#theme' => 'rw_config_listing',
      '#data' => [
        'app_title' => $config->get('app_title'),
        'items' => $items,
        'show_pictures' => $config->get('show_pictures'),

      ]
    ];
  }

}
