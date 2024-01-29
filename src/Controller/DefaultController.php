<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/')]
class DefaultController extends AbstractController
{

      private $POSTS = [
        [
            'id' => 1,
            'title' => 'Post 1',
            'body' => 'This is the body of post 1',
            'slug' => 'post-1'
        ],
        [
            'id' => 2,
            'title' => 'Post 2',
            'body' => 'This is the body of post 2',
            'slug' => 'post-2'
        ],
        [
            'id' => 3,
            'title' => 'Post 3',
            'body' => 'This is the body of post 3',
            'slug' => 'post-3'
        ],
        [
            'id' => 4,
            'title' => 'Post 4',
            'body' => 'This is the body of post 4',
            'slug' => 'post-4'
        ],
        [
            'id' => 5,
            'title' => 'Post 5',
            'body' => 'This is the body of post 5',
            'slug' => 'post-5'
        ],
    ];
    // this route is the index
    // index_handler is the method that will be called when the route is requested
    // index_handler only accepts GET requests

    #[Route('/{page}' , defaults : ['page' => '1'] , methods: ['GET'] , name : 'posts_list')]
    public function list($page , Request  $request)

    {
        $limit = $request->query->get('limit' , 10);
        return $this->json(['posts' => $this->POSTS , 'page' => $page , 'limit' => $limit]);
    } 
    #[Route("{id}" , requirements : ['id' => '\d+'] , methods: ['GET'] , name : 'post_by_id')]
    public function getById($id)
    {
        $post = array_filter($this->POSTS  , function ($post) use($id){
            return $post['id'] == $id;
        });
        if(empty($post)){
            return $this->json(['error' => 'Post not found'],404);
        }
        return $this->json($post);
    }

    #[Route("{slug}" , methods: ['GET'] , name : 'post_by_slug')]
    public function getBySlug($slug){
        $post = array_filter($this->POSTS , function($post) use($slug) {
            return $post['slug'] == $slug;
        });
        if(empty($post)){
            return $this->json(['error' => 'Post not found'],404);
        }
        return $this->json($post);
        
    }

    #[Route('' , methods : ['POST'] , name : 'post_create')]
    public function create(Request $request){
        $data = serialize($request->getContent());
        if(empty($data['title']) || empty($data['body']) || empty($data['slug'])){
            return $this->json(['error' => 'Missing required parameters'],400);
        }
        $post = [
            'id' => count($this->POSTS) + 1,
            'title' => $data['title'],
            'body' => $data['body'],
            'slug' => $data['slug']
        ];
     
       
        return $this->json($post , 201);
    }

    
}
