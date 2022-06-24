<?php

namespace Drupal\helfi_gredi_image\Entity;

/**
 * Class Category.
 *
 * Describes Gredi DAM's Category data type.
 */
class Category implements EntityInterface, \JsonSerializable {

  /**
   * ID of the Category.
   *
   * @var string
   */
  public $id;

  /**
   * Name of the Category.
   *
   * @var string
   */
  public $name;

  /**
   * Description of the category.
   *
   * @var string
   */
  public $description;

  /**
   * Path of files from the category.
   *
   * @var string
   */
  public $apiContentLink;

  /**
   * Created time.
   *
   * @var string
   */
  public $created;

  /**
   * Updated time.
   *
   * @var string
   */
  public $modified;

  /**
   * Get category object.
   *
   * @param mixed $json
   *   Json object contain categories data.
   *
   * @return object
   *   Category data.
   */
  public static function fromJson($json) {
    if (is_string($json)) {
      $json = json_decode($json);
    }

    $subCategories = [];
    if (isset($json->total_count) && $json->total_count > 0) {
      foreach ($json as $subcategory_data) {
        if ($subcategory_data['folder'] == TRUE) {
          $subCategories[] = Category::fromJson($subcategory_data);
        }

      }
      return $subCategories;
    }
    elseif (isset($json->total_count) && $json->total_count === 0) {
      return $subCategories;
    }
    $properties = [
      'id',
      'name',
      'description',
      'apiContentLink',
      'created',
      'modified',
    ];

    $category = new static();
    foreach ($properties as $property) {
      if (isset($json[$property])) {
        $category->{$property} = $json[$property];
      }
    }

    return $category;
  }

  /**
   * Serialize the category data.
   *
   * @return array
   *   Array contain category properties.
   */
  public function jsonSerialize() {
    $properties = [
      'id' => $this->id,
      'description' => 'category',
      'apiContentLink' => $this->apiContentLink,
      'created' => $this->created,
      'modified' => $this->modified,
    ];

    if (!empty($this->categories)) {
      $properties['categories'] = $this->categories;
    }

    return $properties;
  }

}