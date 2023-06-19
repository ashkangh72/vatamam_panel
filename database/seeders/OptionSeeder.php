<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class OptionSeeder extends Seeder
{
    private $options=[
      "SMS_PANEL_API_KEY"=>"e0fe38c6c92bc34ef1fa34c5",
      "SMS_PANEL_SECRET_KEY"=>"g#@Q)vF#7}F}nJ)",
      "SMS_PANEL_LINE_NUMBER"=>"30002176200186",
      "user_verify_pattern_code"=>"63066",
      "info_site_title"=>"شانیلند",
      "web_url"=>"https://mode.shaniland.com",
      "api_url"=>"https://api.shaniland.com",
      "web_public_filepath"=>"../../../../../shanimode/domains/mode.shaniland.com/public_html/public",
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->options as $key=>$value)
            option_update($key,$value);
    }
}
