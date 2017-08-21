<?php
/**
 * IndexController.php
 */

namespace SoftnCMS\controllers\admin;

use SoftnCMS\controllers\ControllerAbstract;
use SoftnCMS\controllers\ViewController;
use SoftnCMS\models\managers\CategoriesManager;
use SoftnCMS\models\managers\CommentsManager;
use SoftnCMS\models\managers\PagesManager;
use SoftnCMS\models\managers\PostsManager;
use SoftnCMS\models\managers\TermsManager;
use SoftnCMS\models\managers\UsersManager;
use SoftnCMS\models\tables\Comment;
use SoftnCMS\models\tables\Post;
use SoftnCMS\util\Arrays;
use SoftnCMS\util\Sanitize;
use SoftnCMS\util\Util;

/**
 * Class IndexController
 * @author Nicolás Marulanda P.
 */
class IndexController extends ControllerAbstract {
    
    public function index() {
        $this->read();
        ViewController::view('index');
    }
    
    protected function read() {
        $postsManager      = new PostsManager();
        $commentsManager   = new CommentsManager();
        $categoriesManager = new CategoriesManager();
        $termsManager      = new TermsManager();
        $usersManager      = new UsersManager();
        $pagesManager = new PagesManager();
        $posts             = $postsManager->read(['limit' => 5]);
        $comments          = $commentsManager->read(['limit' => 5]);
        
        $posts = array_map(function(Post $post) {
            $title = $post->getPostTitle();
            
            if (isset($title{30})) {
                $title = substr($title, 0, 30) . '...';
            }
            
            $post->setPostTitle($title);
            
            return $post;
        }, $posts);
        
        $comments = array_map(function(Comment $comment) {
            $contents = Sanitize::clearTags($comment->getCommentContents());
            
            if (isset($contents{30})) {
                $contents = substr($contents, 0, 30) . '...';
            }
            
            $comment->setCommentContents($contents);
            
            return $comment;
        }, $comments);
        
        ViewController::sendViewData('posts', $posts);
        ViewController::sendViewData('comments', $comments);
        ViewController::sendViewData('countPosts', $postsManager->count());
        ViewController::sendViewData('countComments', $commentsManager->count());
        ViewController::sendViewData('countCategories', $categoriesManager->count());
        ViewController::sendViewData('countTerms', $termsManager->count());
        ViewController::sendViewData('countUsers', $usersManager->count());
        ViewController::sendViewData('countPages', $pagesManager->count());
    }
    
    public function apiGitHub() {
        $url                  = "https://api.github.com/repos/nmarulo/softn-cms/branches";
        $lastCommitUrlMaster  = $this->lastCommitListBranch($url, 'master');
        $lastCommitUrlDevelop = $this->lastCommitListBranch($url, 'develop');
        
        if ($lastCommitUrlDevelop !== FALSE && $lastCommitUrlMaster !== FALSE) {
            $commitsMaster     = $this->getDataGitHub($lastCommitUrlMaster);
            $commitsDevelop    = $this->getDataGitHub($lastCommitUrlDevelop);
            $lastUpdateMaster  = array_map(function($value) {
                return $value['commitDate'];
            }, $commitsMaster);
            $lastUpdateDevelop = array_map(function($value) {
                return $value['commitDate'];
            }, $commitsDevelop);
            
            ViewController::sendViewData('lastUpdateMaster', array_shift($lastUpdateMaster));
            ViewController::sendViewData('lastUpdateDevelop', array_shift($lastUpdateDevelop));
            ViewController::sendViewData('master', $commitsMaster);
            ViewController::sendViewData('develop', $commitsDevelop);
            ViewController::singleView('apigithub');
        }
    }
    
    private function lastCommitListBranch($url, $nameBranch) {
        $branches = Util::curl($url);
        
        if ($branches === FALSE) {
            return FALSE;
        }
        
        $branches = json_decode($branches, TRUE);
        $data     = array_filter($branches, function($value) use ($nameBranch) {
            return Arrays::get($value, 'name') == $nameBranch;
        });
        $data     = array_map(function($value) {
            $commit = Arrays::get($value, 'commit');
            
            return Arrays::get($commit, 'url');
        }, $data);
        
        return array_shift($data);
    }
    
    private function getDataGitHub($commitUrl, $actual = 1, $total = 5) {
        $dataCommits = [];
        $data        = [];
        $this->parentCommit($commitUrl, $actual, $total, $dataCommits);
        
        array_walk($dataCommits, function($value) use (&$data) {
            $author      = $value['author']['login'];
            $authorUrl   = $value['author']['html_url'];
            $commitUrl   = $value['html_url'];
            $commitTitle = $value['commit']['message'];
            $commitDate  = $value['commit']['author']['date'];
            $data[]      = [
                'author'      => $author,
                'authorUrl'   => $authorUrl,
                'commitUrl'   => $commitUrl,
                'commitTitle' => $commitTitle,
                'commitDate'  => $commitDate,
            ];
        });
        
        return $data;
    }
    
    private function parentCommit($commitUrl, $actual, $total, &$data) {
        $dataCommits = Util::curl($commitUrl);
        
        if ($dataCommits !== FALSE) {
            $dataCommits = json_decode($dataCommits, TRUE);
            $data[]      = $dataCommits;
            $parent      = array_shift(Arrays::get($dataCommits, 'parents'));
            
            if ($actual < $total) {
                ++$actual;
                $commitUrl = Arrays::get($parent, 'url');
                $this->parentCommit($commitUrl, $actual, $total, $data);
            }
        }
    }
}
