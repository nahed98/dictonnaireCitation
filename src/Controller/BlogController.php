<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Entity\Citation;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\AuteurRepository;
use App\Repository\CitationRepository;
use App\Form\CitationType;
use App\Form\AuteurType;
use App\Entity\Auteur;
use App\Form\AdminType;
use App\Entity\Editeur;
use App\Form\MessageType;
use App\Entity\Message;
use App\Entity\User;
use App\Form\RegistrationType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="blog")
     */
    public function index(): Response
    {
        $repo=$this->getDoctrine()->getRepository(Auteur::class);
        $auteurs=$repo->findAll();
        return $this->render('blog/index.html.twig', [
            'auteurs'=> $auteurs
        ]);
    }
    /**
     * @Route("/", name="home")
     */
    public function home(Request $request, ManagerRegistry $manager){
        $message=new Message();
        $repo=$this->getDoctrine()->getRepository(Citation::class);
        $citations=$repo->findAll();
        $form=$this->createForm(MessageType :: class, $message);
        $form->handleRequest($request);
        $msg="";
        if($form->isSubmitted()&& $form->isValid()){
                       
                      
            $manager->getManager()->persist($message);
            $manager->getManager()->flush();
            $msg="Votre message a été envoyé!";
          return $this->redirectToRoute('home',['msg'=>$msg]);
          
      }
        return $this->render('blog/home.html.twig', [
            'citations'=> $citations,
            'formMessage'=> $form->createView(),
            'msg'=> $msg
        ]);
    }
    /**
     * @Route("/blog/recherche", name="recherche")
     */
    public function recherche(Request $request){
        $repo=$this->getDoctrine()->getRepository(Auteur::class);
        $auteurs=$repo->findAll();
        $repo=$this->getDoctrine()->getRepository(Citation::class);
        $citations=$repo->findAll();
    return $this->render('blog/recherche.html.twig',[
        'auteurs'=> $auteurs,
        'citations'=>$citations
         ]);
    }
    /**
    * @IsGranted("ROLE_ADMIN")
     * @Route("/blog/admin", name="user")
     */
    public function administration(){
        $repo=$this->getDoctrine()->getRepository(User::class);
        $users=$repo->findAll();
        return $this->render('blog/admin.html.twig', [
            'users'=> $users
        ]);
    }
     /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/blog/Message", name="show_message")
     */
    public function voirmessage()
    {
        $repo=$this->getDoctrine()->getRepository(Message::class);
        $messages=$repo->findAll();
        return $this->render('blog/showMessage.html.twig', [
            'messages'=> $messages
        ]);

    }
      /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/blog/visiteurInscrit", name="show_liste")
     */
    public function voirUsers()
    {
        $repo=$this->getDoctrine()->getRepository(User::class);
        $users=$repo->findAll();
        return $this->render('blog/showUsers.html.twig', [
            'users'=> $users
        ]);

    }
    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/blog/{id}/deleteUser", name="user_delete")
     */
    public function deleteUser($id, ManagerRegistry $manager)    {
        $user= $manager->getManager()->find(User::class,$id);
        $manager->getManager()->remove($user);
        $manager->getManager()->flush();
        $repo=$this->getDoctrine()->getRepository(User::class);
        $users=$repo->findAll();
        return $this->render('blog/admin.html.twig', [
            'users'=> $users]);
    }
     /**
    * @IsGranted("ROLE_EDITOR")
     * @Route("/blog/editeur/new", name="user_new")
     * @Route("/blog/{id}/editeur",name="user_edit")
     */
    public function formEditeur(User $user=null,Request $request, ManagerRegistry $manager,UserPasswordEncoderInterface $encoder){
      
        if(!$user){
            $user=new User();
        } 
         
    
         $form=$this->createForm(RegistrationType :: class, $user);
                      $form->handleRequest($request);
            
                    if($form->isSubmitted()&& $form->isValid()){
                       
                        $hash=$encoder->encodePassword($user,$user->getPassword()) ;      
                        $user->setPassword($hash);
                        $user->setRoles(['ROLE_EDITOR']);  
                        $manager->getManager()->persist($user);
                        $manager->getManager()->flush();
              
                      return $this->redirectToRoute('user');
                      
                  }
                  
                      
         return $this->render('blog/createEditeur.html.twig',[
        'formEditeur'=> $form->createView(),
         'editMode'=> $user->getId()!==null
         ]);
     }
       /**
        * @IsGranted("ROLE_EDITOR")
     * @Route("/blog/new", name="blog_create")
     * @Route("/blog/{id}/edit",name="blog_edit")
     */
    public function formCitation(Citation $citation=null,Request $request, ManagerRegistry $manager){
      
        if(!$citation){
            $citation=new Citation();
        } 
         
    
         $form=$this->createForm(CitationType :: class, $citation);
                      $form->handleRequest($request);
                
                    if($form->isSubmitted()&& $form->isValid()){
                       
                        $manager->getManager()->persist($citation);
                        $manager->getManager()->flush();
              
                      return $this->redirectToRoute('home');
                      
                  }
                  
                      
         return $this->render('blog/create.html.twig',[
        'formCitation'=> $form->createView(),
         'editMode'=> $citation->getId()!==null
         ]);
     }
  
     /**
     * @IsGranted("ROLE_EDITOR")
     * @Route("/blog/{id}/delete", name="delet_citation")
     */
    public function delet($id, Request $request,ManagerRegistry $manager){
        $message=new Message();
        $repo=$this->getDoctrine()->getRepository(Citation::class);
        $citations=$repo->findAll();
        $form=$this->createForm(MessageType :: class, $message);
        $form->handleRequest($request);
        $msg="";
        if($form->isSubmitted()&& $form->isValid()){
                       
                      
            $manager->getManager()->persist($message);
            $manager->getManager()->flush();
            $msg="Votre message a été envoyé!";
          return $this->redirectToRoute('home',['msg'=>$msg]);
          
      }
        $citation= $manager->getManager()->find(Citation::class,$id);
        $manager->getManager()->remove($citation);
        $manager->getManager()->flush();
        $repo=$this->getDoctrine()->getRepository(Citation::class);
        $citations=$repo->findAll();
        return $this->render('blog/home.html.twig', [
            'citations'=> $citations,
            'formMessage'=> $form->createView()]);}
     /**
       * @IsGranted("ROLE_EDITOR")
       * @Route("/blog/{id}/deleteAuteur", name="delet_auteur")
     */
    public function deletAuteur($id, ManagerRegistry $manager){
        $auteur= $manager->getManager()->find(Auteur::class,$id);
        $manager->getManager()->remove($auteur);
        $manager->getManager()->flush();
        $repo=$this->getDoctrine()->getRepository(Auteur::class);
        $auteurs=$repo->findAll();
        return $this->render('blog/index.html.twig', [
            'auteurs'=> $auteurs]);}
     /**
        * @IsGranted("ROLE_EDITOR")
     * @Route("/blog/newauteur", name="blog_createauteur")
     * @Route("/blog/{id}/editauteur",name="blogauteur_edit")
     */
    public function formAuteur(Auteur $auteur=null,Request $request, ManagerRegistry $manager){
      
        if(!$auteur){
            $auteur=new Auteur();
        } 
         
    
         $form=$this->createForm(AuteurType :: class, $auteur);
                      $form->handleRequest($request);
                     
                      if($form->isSubmitted()&& $form->isValid()){
                     
                          $manager->getManager()->persist($auteur);
                          $manager->getManager()->flush();
                          return $this->redirectToRoute("blog");
                          
                      }
         return $this->render('blog/createauteur.html.twig',[
        'formAuteur'=> $form->createView(),
         'editMode'=> $auteur->getId()!==null
         ]);
     }
   
}
