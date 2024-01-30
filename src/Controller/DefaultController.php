<?php

namespace App\Controller;
use App\Entity\BlogPost;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;



#[Route('/blogs')]
class DefaultController extends AbstractController
{
  

    #[Route('/' , methods: ['GET'] , name : 'posts_list')]
    public function list(Request  $request , EntityManagerInterface $entityManager)
    {
        $limit = $request->query->get('limit' , 10);
        $page = $request->query->get('page' , 1);

        $postRepository = $entityManager->getRepository(BlogPost::class);
        $posts = $postRepository->findAll();

        return $this->json($posts);
    } 
    #[Route("/{id}" , requirements : ['id' => '\d+'] , methods: ['GET'] , name : 'post_by_id')]
    public function getById($id , EntityManagerInterface $entityManager )
    {
        $postRepository = $entityManager->getRepository(BlogPost::class);
        $post = $postRepository->find($id);
        if(!$post){
            return $this->json(['error' => 'Post not found'],404);
        }

        return $this->json($post);
    }

    #[Route('' , methods : ['POST'] , name : 'post_create')]
    
    public function create(Request $request , SerializerInterface $serializer , EntityManagerInterface $entityManager){

        $blogpostDTO = $serializer->deserialize($request->getContent() , BlogPost::class , 'json');
     
        $entityManager->persist($blogpostDTO);
        $entityManager->flush();
        return $this->json($blogpostDTO , 201);
    }

    #[Route('/{id}' , methods : ["DELETE"] , name : 'post_delete' , requirements : ['id' => '\d+'])]
    
    public function delete(Request $request, EntityManagerInterface $entityManager){

     
        $repositroy = $entityManager->getRepository(BlogPost::class);
        $postDTO = $repositroy->find($request->get('id'));
        $entityManager->remove($postDTO);
        $entityManager->flush();

        return $this->json($postDTO , Response::HTTP_NO_CONTENT);
    }

    
}
