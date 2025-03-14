<?php
/**
 * Page Model
 *
 *  Manage the Pages
 *
 * @package TokenLite
 * @author Softnio
 * @version 1.0
 */
namespace App\Models;

use App\BigChainDB\BigChainModel;
use IcoData;

class Page extends BigChainModel
{

    /*
     * Table Name Specified
     */
    protected static $table = 'pages';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'menu_title', 'slug', 'custom_slug', 'description', 'status', 'lang', 'public',
    ];

    /**
     *
     * Get the page
     *
     * @version 1.0.0
     * @since 1.0
     * @return void
     */
    public static function get_page($slug, $get = '')
    {
        $data = self::where('slug', $slug)->first();
        if ($data == null) {
            $data = IcoData::default_pages($slug);
        }
        if ($get == '') {
            $res = ($data ? $data : null);
        } else {
            $res = $data->$get ? $data->$get : null;
        }
        return $res;
    }

    /**
     *
     * Get slug
     *
     * @version 1.0.0
     * @since 1.0
     * @return void
     */
    public static function get_slug($slug)
    {
        $data = self::where('slug', $slug)->first();
        if ($data == null) {
            $data = IcoData::default_pages($slug);
        } else {
            return $data->custom_slug;
        }

        return $slug;
    }
}
