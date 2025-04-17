<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Role;
use App\Models\Webinar;
use App\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $data = [];
        $search = $request->get('search', null);

        // Define stop words
        $stopWords = [
            'a', 'an', 'and', 'are', 'as', 'at', 'by', 'for', 'from', 'has', 'have',
            'the', 'is', 'it', 'in', 'of', 'to', 'with', 'on', 'this', 'that', 'which',
            'or', 'not', 'be', 'will', 'would', 'can', 'could', 'should', 'may', 'might',
            'i', 'you', 'he', 'she', 'we', 'they', 'my', 'your', 'his', 'her', 'our', 'their',
            'its', 'their', 'our', 'yours', 'mine', 'hers', 'ours', 'theirs',
            'each', 'every', 'some', 'many', 'few', 'most', 'several', 'both', 'either', 'neither',
            'so', 'then', 'than', 'but', 'if', 'when', 'where', 'why', 'how', 'all', 'any', 'no',
            'more', 'less', 'other', 'one', 'two', 'three', 'four', 'five', 'six', 'seven',
            'eight', 'nine', 'ten', 'about', 'around', 'between', 'during', 'under', 'over',
            'courses', 'course', 'learn', 'i', 'your','need','my'
        ];
        if (!empty($search) && strlen($search) >= 3) {
            // Explode the search query into words
            $words = explode(' ', $search);

            // Remove stop words from the array
            $filteredWords = array_diff(array_map('strtolower', $words), $stopWords);

            // Only proceed if there are filtered words left
            if (!empty($filteredWords)) {
                $webinars = Webinar::where('status', 'active')
                    ->where('private', false)
                    ->where(function($query) use ($filteredWords) {
                        foreach ($filteredWords as $word) {
                            if (!empty($word) && strlen($word) >= 3) {
                                $query->orWhereTranslationLike('title', "%$word%");
                            }
                        }
                    })
                    ->orWhere(function($query) use ($filteredWords) {
                        foreach ($filteredWords as $word) {
                            if (!empty($word) && strlen($word) >= 3) {
                                // Assuming you have a `categories` relationship on the Product model
                                $query->orWhereHas('category', function($q) use ($word) {
                                    $q->where('slug', 'like', "%$word%");
                                });
                            }
                        }
                    })
                    ->with([
                        'teacher' => function ($query) {
                            $query->select('id', 'full_name', 'avatar', 'avatar_settings');
                        },
                        'reviews'
                    ])
                    ->get();

                $products = Product::where('status', 'active')
                    ->where(function($query) use ($filteredWords) {
                        foreach ($filteredWords as $word) {
                            if (!empty($word) && strlen($word) >= 3) {
                                $query->orWhereTranslationLike('title', "%$word%");
                            }
                        }
                    })
                    ->orWhere(function($query) use ($filteredWords) {
                        foreach ($filteredWords as $word) {
                            if (!empty($word) && strlen($word) >= 3) {
                                // Assuming you have a `categories` relationship on the Product model
                                $query->orWhereHas('category', function($q) use ($word) {
                                    $q->where('slug', 'like', "%$word%");
                                });
                            }
                        }
                    })
                    ->with([
                        'creator' => function ($query) {
                            $query->select('id', 'full_name', 'avatar', 'avatar_settings');
                        }
                    ])
                    ->get();

                $users = User::where('status', 'active')
                    ->where(function($query) use ($filteredWords) {
                        foreach ($filteredWords as $word) {
                            if (!empty($word) && strlen($word) >= 3) {
                                $query->orWhere('full_name', 'like', "%$word%")
                                      ->orWhere('email', 'like', "%$word%")
                                      ->orWhere('mobile', 'like', "%$word%");
                            }
                        }
                    })
                    ->with([
                        'webinars' => function ($query) {
                            $query->where('status', 'active');
                        }
                    ])
                    ->get();

                $teachers = $users->where('role_name', Role::$teacher);
                $organizations = $users->where('role_name', Role::$organization);

                $seoSettings = getSeoMetas('search');
                $pageTitle = !empty($seoSettings['title']) ? $seoSettings['title'] : trans('site.search_page_title');
                $pageDescription = !empty($seoSettings['description']) ? $seoSettings['description'] : trans('site.search_page_title');
                $pageRobot = getPageRobot('search');

                $data = [
                    'pageTitle' => $pageTitle,
                    'pageDescription' => $pageDescription,
                    'pageRobot' => $pageRobot,
                    'resultCount' => count($webinars) + count($teachers) + count($organizations) + count($products),
                    'webinars' => $webinars,
                    'teachers' => $teachers,
                    'organizations' => $organizations,
                    'products' => $products,
                ];
            }
        }

        return view(getTemplate() . '.pages.search', $data);
    }
}
