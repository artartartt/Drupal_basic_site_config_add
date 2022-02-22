<?php

namespace Drupal\test2\Plugin\Block;

use Drupal\Core\Link;
use Drupal\Core\Url;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\commerce_cart\CartProviderInterface;
use Drupal\Core\Config\ConfigFactory;



/**
 *
 *
 * @Block(
 *   id = "show_info_block",
 *   admin_label = @Translation("Show Info"),
 *   category = @Translation("Show Info"),
 * )
 */
class ShowInfo extends BlockBase implements ContainerFactoryPluginInterface{

  // myModule.settings for db connect
  protected $service;

  /**
   * @param array $configuration
   * @param string $plugin_id
   * @param mixed $plugin_definition
   * @param \Drupal\Core\Session\AccountInterface $account
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, ConfigFactory $service) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->service = $service;
  }

  /**
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   * @param array $configuration
   * @param string $plugin_id
   * @param mixed $plugin_definition
   *
   * @return static
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('config.factory')
    );
  }

  public function build() {

    //get all info in db
    $all_info = $this->service->get('foo.bar')->get('all_info');

    //added link in name
    for($i = 0;$i<count($all_info);$i++){
        $value['link'] = "https://" . $all_info[$i]['link'];
        $url = Url::fromUri("" . $value['link']);
        $link = Link::fromTextAndUrl($all_info[$i]['name'], $url);
        $list[] = $link;
    }
    $output['links'] = [
      '#theme' => 'item_list',
      '#items' => $list,
    ];
    return $output;
  }

}
