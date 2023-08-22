<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\WordsOfArticles;
use App\Service\WikiAPI\WikiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ArticlesController extends Controller
{

    public function index()
    {
        $articles = Article::latest()->get();
        return view('home', compact('articles'));
    }


    public function getWikiPage(WikiService $mediaWikiService, Request $request)
    {
        $pageTitle = $request->input('pageTitle');
        $content  = $mediaWikiService->getPageContent($pageTitle);
        $page = reset($content['query']['pages']);
        if (empty($page['revisions'])){
            $article = 'no article';
            $words = 'sihs';
        }else{
            $pageUrl = "https://ru.wikipedia.org/wiki/" . $page['title'];
            $plainText = preg_replace("/'{2,5}(.*?)'{2,5}/", "", $page['revisions'][0]['slots']['main']['*']);
            $plainText = preg_replace("/[\{\}\(\)\[\]]+/", "", $plainText);
            $article = Article::firstOrCreate(
                [
                    'title' => $page['title'],
                ],
                [
                    'content' => $plainText,
                    'url' => $pageUrl,
                    'size_article' => (string)$page['revisions'][0]['size'],
                    'word_count' => count(preg_split('/[^а-яА-Я]+/u', $plainText, -1, PREG_SPLIT_NO_EMPTY)),
                ]
            );
            $this->setWordsOfArticle($article->content, $article->id);
            unset($article->content);
            $article = $article->wasRecentlyCreated ? $article : 'previously created';
        }

        return response()->json(['article' => $article]);
    }

    public function searchArticlesInDatabase(Request $request)
    {
        $word = mb_strtolower($request->input('word'));
        $articles = Article::join('words_of_articles', 'articles.id', '=', 'words_of_articles.article_id')
            ->select('articles.*', 'words_of_articles.number_of_words as number_of_words')
            ->where('words_of_articles.word', $word)
            ->orderBy('number_of_words', 'desc')
            ->get();
        return response()->json(['articles' => $articles]);
    }


    private function setWordsOfArticle( string $content, int $articleId)
    {
        $words = preg_split('/\PL+/u', mb_strtolower($content), -1, PREG_SPLIT_NO_EMPTY);
        $wordCount = array_count_values($words);
        foreach ($wordCount as $word => $count){
            WordsOfArticles::create([
                'word' => $word,
                'article_id' => $articleId,
                'number_of_words' => $count,
            ]);
        }
    }
}
