<?php

namespace Modules\CoreData\Actions\Category;
use Modules\CoreData\Repositories\CategoryRepository;


class FindOrCreateCategoryAction
{
    protected string $name;

    /**
     * Create a new Repository instance.
     *
     * @return void
     */
    public function __construct($name)
    {
        $this->name = $name;
    }
   
   /**
    * The function executes a search for a category by name and returns it if found, otherwise it
    * creates a new category with the given name and returns it.
    * 
    * param name The name parameter is a string that represents the name of a category.
    * 
    * @return The function will return either the found category or the newly saved category.
    */
    public function execute()
    {
        $request = request();
        $request->merge(['name' =>  $this->name]);
        $category = App(CategoryRepository::class)->findBy($request, get: "first");

        if ($category) {

            return $category;
        }
        
      $request->merge(['name' => ['ar' => $this->name, 'en' => $this->name]]);
        $category = App(CategoryRepository::class)->save($request, $id = null);

        return $category;
    }
}
