<?php

namespace Drupal\example_migration\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;

/**
 * Returns responses for Example Migration routes.
 */
class MigratedContentController extends ControllerBase {

  /**
   * Builds the response.
   */
  public function build() {
    // Check if there are relationships by running a query.
    if ($relations = $this->getRelations()) {
      // Instantiate the build array.
      $build = [];
      /** @var \Drupal\Core\Entity\EntityTypeManager $manager*/
      $manager = $this->entityTypeManager();
      /** @var \Drupal\Core\Entity\EntityStorageInterface $node_manager */
      $node_manager = $manager->getStorage('node');
      // Loop through relations and get links to nodes.
      foreach ($relations as $relation) {
        $relation_array = [];
        // Load the related nodes and create a renderable link array.
        $user_node = $node_manager->load($relation->destid1);
        $company_node = $node_manager->load($relation->c_destid1);
        $user_link = $user_node->toLink()->toRenderable();
        $company_link = $company_node->toLink()->toRenderable();
        // Add prefixes to clean things up a bit.
        $user_link['#prefix'] = 'Name: ';
        $company_link['#prefix'] = ' Company: ';
        // Set type to item so that each record is separated.
        $relation_array['#type'] = 'item';
        // Add the individual links to the current render array then add that
        // to the build array.
        $relation_array[] = $user_link;
        $relation_array[] = $company_link;
        $build[] = $relation_array;
      }
    }

    return $build;
  }

  /**
   * Gets the relationships between company and user.
   *
   * @return mixed
   *   Either the results of the query or NULL if no results.
   */
  public function getRelations() {
    $connection = Database::getConnection();
    // Check if the migration has been run. Assume that if these tables exist
    // the migration has run.
    if ($connection->schema()->tableExists('migrate_map_companies') && $connection->schema()->tableExists('migrate_map_users')) {
      // Start the query and join both migrate tables together to get records
      // of both based on the origin ID.
      $query = $connection->select('migrate_map_companies', 'c');
      $query->join('migrate_map_users', 'u', 'c.sourceid1 = u.sourceid1');
      $results = $query->fields('u', ['destid1', 'sourceid1'])
        ->fields('c', ['destid1'])
        ->execute()
        ->fetchAll();

      return $results;
    }
    // If the tables do not exist, do not bother with the other stuff.
    return NULL;
  }

}
