[{"name":"A1_AFTER_DB_EXPORT","subject":"$content","params":"","readonly":"","filepath":"\/redaxo\/include\/addons\/import_export\/functions\/function_import_export.inc.php","line_number":421},{"name":"A1_AFTER_DB_IMPORT","subject":"$msg","params":"array(\n  'content' => $conts, \n  'filename' => $filename, \n  'filesize' => $filesize)","readonly":"","filepath":"\/redaxo\/include\/addons\/import_export\/functions\/function_import_export.inc.php","line_number":196},{"name":"A1_AFTER_FILE_EXPORT","subject":"$tar","params":"","readonly":"","filepath":"\/redaxo\/include\/addons\/import_export\/functions\/function_import_export.inc.php","line_number":460},{"name":"A1_AFTER_FILE_IMPORT","subject":"$tar","params":"","readonly":"","filepath":"\/redaxo\/include\/addons\/import_export\/functions\/function_import_export.inc.php","line_number":268},{"name":"A1_BEFORE_DB_EXPORT","subject":"","params":"","readonly":"","filepath":"\/redaxo\/include\/addons\/import_export\/functions\/function_import_export.inc.php","line_number":306},{"name":"A1_BEFORE_DB_IMPORT","subject":"$msg","params":"array(\n  'content' => $conts, \n  'filename' => $filename, \n  'filesize' => $filesize)","readonly":"","filepath":"\/redaxo\/include\/addons\/import_export\/functions\/function_import_export.inc.php","line_number":109},{"name":"A1_BEFORE_FILE_EXPORT","subject":"$tar","params":"","readonly":"","filepath":"\/redaxo\/include\/addons\/import_export\/functions\/function_import_export.inc.php","line_number":451},{"name":"A1_BEFORE_FILE_IMPORT","subject":"$tar","params":"","readonly":"","filepath":"\/redaxo\/include\/addons\/import_export\/functions\/function_import_export.inc.php","line_number":244},{"name":"A62_CUSTOM_FIELD","subject":"array(\n  $field, \n  $tag, \n  $tag_attr, \n  $id, \n  $label, \n  $labelIt, \n  'values' => $dbvalues, \n  'rawvalues' => $dbvalues, \n  'type' => $typeLabel, \n  'sql' => $sqlFields)","params":"","readonly":"","filepath":"\/redaxo\/include\/addons\/metainfo\/extensions\/extension_common.inc.php","line_number":415},{"name":"A62_TYPE_FIELDS","subject":"array(\n  REX_A62_FIELD_SELECT, \n  REX_A62_FIELD_RADIO, \n  REX_A62_FIELD_CHECKBOX, \n  REX_A62_FIELD_REX_MEDIA_BUTTON, \n  REX_A62_FIELD_REX_MEDIALIST_BUTTON, \n  REX_A62_FIELD_REX_LINK_BUTTON, \n  REX_A62_FIELD_REX_LINKLIST_BUTTON)","params":"","readonly":"","filepath":"\/redaxo\/include\/addons\/metainfo\/classes\/class.rex_table_expander.inc.php","line_number":31},{"name":"ADDONS_INCLUDED","subject":"","params":"","readonly":"","filepath":"\/redaxo\/include\/addons.inc.php","line_number":119},{"name":"ALL_GENERATED","subject":"$MSG","params":"","readonly":"","filepath":"\/redaxo\/include\/functions\/function_rex_generate.inc.php","line_number":32},{"name":"ARTICLE_GENERATED","subject":"''","params":"array(\n  'id' => $id)","readonly":"","filepath":"\/redaxo\/include\/functions\/function_rex_generate.inc.php","line_number":303},{"name":"ART_ADDED","subject":"$message","params":"array(\n  'id' => $id, \n  'clang' => $key, \n  'status' => 0, \n  'name' => $data['name'], \n  're_id' => $data['category_id'], \n  'prior' => $data['prior'], \n  'path' => $data['path'], \n  'template_id' => $data['template_id'], \n  'data' => $data)","readonly":"","filepath":"\/redaxo\/include\/functions\/function_rex_structure.inc.php","line_number":482},{"name":"ART_CONTENT_UPDATED","subject":"''","params":"array(\n  'id' => $article_id, \n  'clang' => $clang)","readonly":"","filepath":"\/redaxo\/include\/pages\/content.inc.php","line_number":337},{"name":"ART_DELETED","subject":"$return","params":"array(\n  \"id\" => $article_id, \n  \"clang\" => $clang, \n  \"re_id\" => $re_id, \n  'name' => $Art->getValue('name'), \n  'status' => $Art->getValue('status'), \n  'prior' => $Art->getValue('prior'), \n  'path' => $Art->getValue('path'), \n  'template_id' => $Art->getValue('template_id'))","readonly":"","filepath":"\/redaxo\/include\/functions\/function_rex_structure.inc.php","line_number":615},{"name":"ART_INIT","subject":"''","params":"array(\n  'article' => &$this, \n  'article_id' => $article_id, \n  'clang' => $this->clang)","readonly":"","filepath":"\/redaxo\/include\/classes\/class.rex_article_base.inc.php","line_number":62},{"name":"ART_META_FORM","subject":"''","params":"array(\n  'id' => $article_id, \n  'clang' => $clang, \n  'article' => $article)","readonly":"","filepath":"\/redaxo\/include\/pages\/content.inc.php","line_number":836},{"name":"ART_META_FORM_SECTION","subject":"''","params":"array(\n  'id' => $article_id, \n  'clang' => $clang)","readonly":"","filepath":"\/redaxo\/include\/pages\/content.inc.php","line_number":854},{"name":"ART_META_UPDATED","subject":"$info","params":"array(\n  'id' => $article_id, \n  'clang' => $clang, \n  'name' => $meta_article_name)","readonly":"","filepath":"\/redaxo\/include\/pages\/content.inc.php","line_number":598},{"name":"ART_PRE_DELETED","subject":"$return","params":"array(\n  \"id\" => $id, \n  \"re_id\" => $re_id, \n  'name' => $ART->getValue('name'), \n  'status' => $ART->getValue('status'), \n  'prior' => $ART->getValue('prior'), \n  'path' => $ART->getValue('path'), \n  'template_id' => $ART->getValue('template_id'))","readonly":"","filepath":"\/redaxo\/include\/functions\/function_rex_generate.inc.php","line_number":369},{"name":"ART_SLICE_MENU","subject":"$listElements","params":"array(\n  'article_id' => $this->article_id, \n  'clang' => $this->clang, \n  'ctype' => $RE_CONTS_CTYPE[$I_ID], \n  'module_id' => $RE_MODUL_ID[$I_ID], \n  'slice_id' => $RE_CONTS[$I_ID], \n  'perm' => ($REX['USER']->isAdmin() || $REX['USER']->hasPerm(\"module[\".$RE_MODUL_ID[$I_ID].\"]\")))","readonly":"","filepath":"\/redaxo\/include\/classes\/class.rex_article_editor.inc.php","line_number":98},{"name":"ART_STATUS","subject":"$message","params":"array(\n  'id' => $article_id, \n  'clang' => $clang, \n  'status' => $newstatus)","readonly":"","filepath":"\/redaxo\/include\/functions\/function_rex_structure.inc.php","line_number":682},{"name":"ART_STATUS_TYPES","subject":"$artStatusTypes","params":"","readonly":"","filepath":"\/redaxo\/include\/functions\/function_rex_structure.inc.php","line_number":723},{"name":"ART_TO_CAT","subject":"''","params":"array(\n  'id' => $art_id, \n  'clang' => $clang)","readonly":"","filepath":"\/redaxo\/include\/functions\/function_rex_content.inc.php","line_number":434},{"name":"ART_TO_STARTPAGE","subject":"''","params":"array(\n  'id' => $neu_id, \n  'id_old' => $alt_id, \n  'clang' => $clang)","readonly":"","filepath":"\/redaxo\/include\/functions\/function_rex_content.inc.php","line_number":386},{"name":"ART_UPDATED","subject":"$message","params":"array(\n  'id' => $article_id, \n  'article' => clone($EA), \n  'article_old' => clone($thisArt), \n  'status' => $thisArt->getValue('status'), \n  'name' => $data['name'], \n  'clang' => $clang, \n  're_id' => $data['category_id'], \n  'prior' => $data['prior'], \n  'path' => $data['path'], \n  'template_id' => $data['template_id'], \n  'data' => $data)","readonly":"","filepath":"\/redaxo\/include\/functions\/function_rex_structure.inc.php","line_number":557},{"name":"BE_STYLE_PAGE_CONTENT","subject":"''","params":"array()","readonly":"","filepath":"\/redaxo\/include\/addons\/be_style\/pages\/index.inc.php","line_number":8},{"name":"CAT_ADDED","subject":"$message","params":"array(\n  'category' => clone($AART), \n  'id' => $id, \n  're_id' => $category_id, \n  'clang' => $key, \n  'name' => $data['catname'], \n  'prior' => $data['catprior'], \n  'path' => $data['path'], \n  'status' => $data['status'], \n  'article' => clone($AART), \n  'data' => $data)","readonly":"","filepath":"\/redaxo\/include\/functions\/function_rex_structure.inc.php","line_number":106},{"name":"CAT_DELETED","subject":"$return","params":"array(\n  'id' => $category_id, \n  're_id' => $re_id, \n  'clang' => $_clang, \n  'name' => $thisCat->getValue('catname'), \n  'prior' => $thisCat->getValue('catprior'), \n  'path' => $thisCat->getValue('path'), \n  'status' => $thisCat->getValue('status'))","readonly":"","filepath":"\/redaxo\/include\/functions\/function_rex_structure.inc.php","line_number":287},{"name":"CAT_FORM_ADD","subject":"''","params":"array(\n  'id' => $category_id, \n  'clang' => $clang, \n  'data_colspan' => ($data_colspan+1))","readonly":"","filepath":"\/redaxo\/include\/pages\/structure.inc.php","line_number":310},{"name":"CAT_FORM_BUTTONS","subject":"''","params":"array(\n  'id' => $category_id, \n  'clang' => $clang)","readonly":"","filepath":"\/redaxo\/include\/pages\/structure.inc.php","line_number":290},{"name":"CAT_FORM_BUTTONS","subject":"''","params":"array(\n  'id' => $edit_id, \n  'clang' => $clang)","readonly":"","filepath":"\/redaxo\/include\/pages\/structure.inc.php","line_number":350},{"name":"CAT_FORM_EDIT","subject":"''","params":"array(\n  'id' => $edit_id, \n  'clang' => $clang, \n  'category' => $KAT, \n  'catname' => $KAT->getValue('catname'), \n  'catprior' => $KAT->getValue('catprior'), \n  'data_colspan' => ($data_colspan+1))","readonly":"","filepath":"\/redaxo\/include\/pages\/structure.inc.php","line_number":370},{"name":"CAT_STATUS","subject":"$message","params":"array(\n  'id' => $category_id, \n  'clang' => $clang, \n  'status' => $newstatus)","readonly":"","filepath":"\/redaxo\/include\/functions\/function_rex_structure.inc.php","line_number":359},{"name":"CAT_STATUS_TYPES","subject":"$catStatusTypes","params":"","readonly":"","filepath":"\/redaxo\/include\/functions\/function_rex_structure.inc.php","line_number":400},{"name":"CAT_TO_ART","subject":"''","params":"array(\n  'id' => $art_id, \n  'clang' => $clang)","readonly":"","filepath":"\/redaxo\/include\/functions\/function_rex_content.inc.php","line_number":485},{"name":"CAT_UPDATED","subject":"$message","params":"array(\n  'id' => $category_id, \n  'category' => clone($EKAT), \n  'category_old' => clone($thisCat), \n  'article' => clone($EKAT), \n  're_id' => $thisCat->getValue('re_id'), \n  'clang' => $clang, \n  'name' => $thisCat->getValue('catname'), \n  'prior' => $thisCat->getValue('catprior'), \n  'path' => $thisCat->getValue('path'), \n  'status' => $thisCat->getValue('status'), \n  'data' => $data)","readonly":"","filepath":"\/redaxo\/include\/functions\/function_rex_structure.inc.php","line_number":212},{"name":"CLANG_ADDED","subject":"''","params":"array(\n  'id' => $id, \n  'name' => $name)","readonly":"","filepath":"\/redaxo\/include\/functions\/function_rex_generate.inc.php","line_number":1064},{"name":"CLANG_ARTICLE_GENERATED","subject":"''","params":"array(\n  'id' => $id, \n  'clang' => $clang, \n  'article' => $CONT)","readonly":"","filepath":"\/redaxo\/include\/functions\/function_rex_generate.inc.php","line_number":278},{"name":"CLANG_DELETED","subject":"''","params":"array(\n  'id' => $clang, \n  'name' => $clangName)","readonly":"","filepath":"\/redaxo\/include\/functions\/function_rex_generate.inc.php","line_number":999},{"name":"CLANG_UPDATED","subject":"''","params":"array(\n  'id' => $id, \n  'name' => $name)","readonly":"","filepath":"\/redaxo\/include\/functions\/function_rex_generate.inc.php","line_number":1091},{"name":"GENERATE_FILTER","subject":"$article_content","params":"array(\n  'id' => $article_id, \n  'clang' => $_clang, \n  'article' => $CONT)","readonly":"","filepath":"\/redaxo\/include\/functions\/function_rex_generate.inc.php","line_number":220},{"name":"IMAGE_ERROR_SEND","subject":"$sendfile","params":"array(\n  'img' => $this->img, \n  'file' => $file)","readonly":"","filepath":"\/redaxo\/include\/addons\/image_manager\/classes\/class.rex_image.inc.php","line_number":220},{"name":"IMAGE_MANAGER_FILTERSET","subject":"$set","params":"array(\n  'rex_image_type' => $type, \n  'img' => $image)","readonly":"","filepath":"\/redaxo\/include\/addons\/image_manager\/classes\/class.rex_image_manager.inc.php","line_number":31},{"name":"IMAGE_MANAGER_INIT","subject":"$subject","params":"","readonly":"","filepath":"\/redaxo\/include\/addons\/image_manager\/config.inc.php","line_number":68},{"name":"IMAGE_SEND","subject":"$sendfile","params":"array(\n  \/\/ TODO Parameter anpassen 'img' => $this->img, \n  'file' => $this->img[\"file\"], \n  'lastModified' => $lastModified, \n  'filepath' => $this->img[\"filepath\"])","readonly":"","filepath":"\/redaxo\/include\/addons\/image_manager\/classes\/class.rex_image.inc.php","line_number":158},{"name":"MEDIA_ADDED","subject":"''","params":"$RETURN","readonly":"","filepath":"\/redaxo\/include\/functions\/function_rex_mediapool.inc.php","line_number":180},{"name":"MEDIA_ADDED","subject":"''","params":"$return","readonly":"","filepath":"\/redaxo\/include\/pages\/mediapool.upload.inc.php","line_number":36},{"name":"MEDIA_DELETED","subject":"''","params":"array(\n  'filename' => $filename)","readonly":"","filepath":"\/redaxo\/include\/classes\/class.oomedia.inc.php","line_number":824},{"name":"MEDIA_FORM_ADD","subject":"''","params":"","readonly":"","filepath":"\/redaxo\/include\/functions\/function_rex_mediapool.inc.php","line_number":469},{"name":"MEDIA_FORM_EDIT","subject":"''","params":"array(\n  'file_id' => $file_id, \n  'media' => $gf)","readonly":"","filepath":"\/redaxo\/include\/pages\/mediapool.media.inc.php","line_number":294},{"name":"MEDIA_LIST_FUNCTIONS","subject":"$opener_link","params":"array(\n  'file_id' => $files->getValue('file_id'), \n  'file_name' => $files->getValue('filename'), \n  'file_oname' => $files->getValue('originalname'), \n  'file_title' => $files->getValue('title'), \n  'file_type' => $files->getValue('filetype'), \n  'file_size' => $files->getValue('filesize'), \n  'file_stamp' => $files->getValue('updatedate'), \n  'file_updateuser' => $files->getValue('updateuser'))","readonly":"","filepath":"\/redaxo\/include\/pages\/mediapool.media.inc.php","line_number":683},{"name":"MEDIA_LIST_QUERY","subject":"$qry","params":"array(\n  'category_id' => $rex_file_category)","readonly":"","filepath":"\/redaxo\/include\/pages\/mediapool.media.inc.php","line_number":577},{"name":"MEDIA_LIST_TOOLBAR","subject":"$cat_out","params":"array(\n  'subpage' => $subpage, \n  'category_id' => $rex_file_category)","readonly":"","filepath":"\/redaxo\/include\/pages\/mediapool.media.inc.php","line_number":81},{"name":"MEDIA_UPDATED","subject":"''","params":"$return","readonly":"","filepath":"\/redaxo\/include\/pages\/mediapool.media.inc.php","line_number":144},{"name":"MEDIA_UPDATED","subject":"''","params":"array(\n  'id' => $file_id, \n  'type' => $FILEINFOS[\"filetype\"], \n  'filename' => $FILEINFOS[\"filename\"])","readonly":"","filepath":"\/redaxo\/include\/pages\/mediapool.media.inc.php","line_number":143},{"name":"META_NAVI","subject":"$meta_item","params":"","readonly":"","filepath":"\/redaxo\/include\/layout\/top.php","line_number":103},{"name":"OOMEDIA_IS_IN_USE","subject":"$warning","params":"array(\n  'filename' => $this->getFileName(), \n  'media' => $this)","readonly":"","filepath":"\/redaxo\/include\/classes\/class.oomedia.inc.php","line_number":652},{"name":"OUTPUT_FILTER","subject":"$content","params":"array(\n  'environment' => $environment, \n  'sendcharset' => $sendcharset)","readonly":"","filepath":"\/redaxo\/include\/functions\/function_rex_client_cache.inc.php","line_number":88},{"name":"OUTPUT_FILTER_CACHE","subject":"$content","params":"''","readonly":"true","filepath":"\/redaxo\/include\/functions\/function_rex_client_cache.inc.php","line_number":91},{"name":"PAGE_BODY_ATTR","subject":"$body_attr","params":"","readonly":"","filepath":"\/redaxo\/include\/layout\/top.php","line_number":72},{"name":"PAGE_CHECKED","subject":"$REX['PAGE']","params":"array(\n  'pages' => $REX['PAGES'])","readonly":"","filepath":"\/redaxo\/index.php","line_number":295},{"name":"PAGE_CONTENT_CTYPE_MENU","subject":"$listElements","params":"array(\n  'article_id' => $article_id, \n  'clang' => $clang, \n  'function' => $function, \n  'mode' => $mode, \n  'slice_id' => $slice_id)","readonly":"","filepath":"\/redaxo\/include\/pages\/content.inc.php","line_number":643},{"name":"PAGE_CONTENT_HEADER","subject":"''","params":"array(\n  'article_id' => $article_id, \n  'clang' => $clang, \n  'function' => $function, \n  'mode' => $mode, \n  'slice_id' => $slice_id, \n  'page' => 'content', \n  'ctype' => $ctype, \n  'category_id' => $category_id, \n  'article_revision' => &$article_revision, \n  'slice_revision' => &$slice_revision)","readonly":"","filepath":"\/redaxo\/include\/pages\/content.inc.php","line_number":101},{"name":"PAGE_CONTENT_MENU","subject":"$listElements","params":"array(\n  'article_id' => $article_id, \n  'clang' => $clang, \n  'function' => $function, \n  'mode' => $mode, \n  'slice_id' => $slice_id)","readonly":"","filepath":"\/redaxo\/include\/pages\/content.inc.php","line_number":692},{"name":"PAGE_CONTENT_OUTPUT","subject":"\"\"","params":"array(\n  'article_id' => $article_id, \n  'clang' => $clang, \n  'function' => $function, \n  'mode' => $mode)","readonly":"","filepath":"\/redaxo\/include\/pages\/content.inc.php","line_number":732},{"name":"PAGE_HEADER","subject":"''","params":"","readonly":"","filepath":"\/redaxo\/include\/layout\/top.php","line_number":71},{"name":"PAGE_MEDIAPOOL_HEADER","subject":"''","params":"array(\n  'subpage' => $subpage, \n  'category_id' => $rex_file_category)","readonly":"","filepath":"\/redaxo\/include\/pages\/mediapool.media.inc.php","line_number":32},{"name":"PAGE_MEDIAPOOL_MENU","subject":"$subline","params":"array(\n  'subpage' => $subpage)","readonly":"","filepath":"\/redaxo\/include\/pages\/mediapool.inc.php","line_number":100},{"name":"PAGE_MEDIAPOOL_OUTPUT","subject":"\"\"","params":"array(\n  'subpage' => $subpage)","readonly":"","filepath":"\/redaxo\/include\/pages\/mediapool.inc.php","line_number":213},{"name":"PAGE_SPECIALS_OUTPUT","subject":"\"\"","params":"array(\n  'subpage' => $subpage)","readonly":"","filepath":"\/redaxo\/include\/pages\/specials.inc.php","line_number":17},{"name":"PAGE_STRUCTURE_HEADER","subject":"''","params":"array(\n  'category_id' => $category_id, \n  'clang' => $clang)","readonly":"","filepath":"\/redaxo\/include\/pages\/structure.inc.php","line_number":195},{"name":"PAGE_TITLE","subject":"$head","params":"array(\n  'category_id' => $category_id, \n  'article_id' => $article_id, \n  'page' => $page)","readonly":"","filepath":"\/redaxo\/include\/functions\/function_rex_title.inc.php","line_number":76},{"name":"PAGE_TITLE_SHOWN","subject":"$subtitle","params":"array(\n  'category_id' => $category_id, \n  'article_id' => $article_id, \n  'page' => $page)","readonly":"","filepath":"\/redaxo\/include\/functions\/function_rex_title.inc.php","line_number":90},{"name":"REXSEO_ARTICLE_ID_NOT_FOUND","subject":"''","params":"","readonly":"","filepath":"\/redaxo\/include\/addons\/seo42\/classes\/class.rexseo_rewrite.inc.php","line_number":94},{"name":"REXSEO_INCLUDED","subject":"","params":"","readonly":"","filepath":"\/redaxo\/include\/addons\/seo42\/classes\/class.seo42_utils.inc.php","line_number":71},{"name":"REXSEO_PATHLIST_BEFORE_REBUILD","subject":"$subject","params":"","readonly":"","filepath":"\/redaxo\/include\/addons\/seo42\/classes\/class.rexseo_rewrite.inc.php","line_number":463},{"name":"REXSEO_PATHLIST_CREATED","subject":"$subject","params":"","readonly":"","filepath":"\/redaxo\/include\/addons\/seo42\/classes\/class.rexseo_rewrite.inc.php","line_number":718},{"name":"REXSEO_PATHLIST_FINAL","subject":"$subject","params":"","readonly":"","filepath":"\/redaxo\/include\/addons\/seo42\/classes\/class.rexseo_rewrite.inc.php","line_number":721},{"name":"REXSEO_POST_REWRITE","subject":"$url","params":"$ep_params","readonly":"","filepath":"\/redaxo\/include\/addons\/seo42\/classes\/class.rexseo_rewrite.inc.php","line_number":292},{"name":"REXSEO_PRE_REWRITE","subject":"false","params":"$params","readonly":"","filepath":"\/redaxo\/include\/addons\/seo42\/classes\/class.rexseo_rewrite.inc.php","line_number":214},{"name":"REXSEO_SITEMAP_ARRAY_CREATED","subject":"$db_articles","params":"","readonly":"","filepath":"\/redaxo\/include\/addons\/seo42\/classes\/class.rexseo_sitemap.inc.php","line_number":49},{"name":"REXSEO_SITEMAP_ARRAY_FINAL","subject":"$db_articles","params":"","readonly":"","filepath":"\/redaxo\/include\/addons\/seo42\/classes\/class.rexseo_sitemap.inc.php","line_number":52},{"name":"REXSEO_SITEMAP_INJECT","subject":"","params":"","readonly":"","filepath":"\/redaxo\/include\/addons\/seo42\/classes\/class.rexseo_sitemap.inc.php","line_number":210},{"name":"REX_BE_NAVI_CLASSNAME","subject":"'rex_be_navigation'","params":"","readonly":"","filepath":"\/redaxo\/include\/classes\/class.rex_navigation.inc.php","line_number":405},{"name":"REX_FORM_CLASSNAME","subject":"'rex_form'","params":"array(\n  'tableName' => $tableName, \n  'fieldset' => $fieldset, \n  'whereCondition' => $whereCondition, \n  'method' => $method, \n  'debug' => $debug)","readonly":"","filepath":"\/redaxo\/include\/classes\/class.rex_form.inc.php","line_number":102},{"name":"REX_FORM_CONTROL_FIELDS","subject":"$controlFields","params":"array(\n  'form' => $this)","readonly":"","filepath":"\/redaxo\/include\/classes\/class.rex_form.inc.php","line_number":141},{"name":"REX_FORM_CONTROL_FIElDS","subject":"$controlFields","params":"array(\n  'form' => $this)","readonly":"","filepath":"\/redaxo\/include\/classes\/class.rex_form.inc.php","line_number":139},{"name":"REX_FORM_DELETED","subject":"$deleted","params":"array(\n  'form' => $this, \n  'sql' => $deleteSql)","readonly":"","filepath":"\/redaxo\/include\/classes\/class.rex_form.inc.php","line_number":1045},{"name":"REX_FORM_INPUT_ATTRIBUTES","subject":"array()","params":"array(\n  'form' => $this, \n  'inputType' => $inputType)","readonly":"","filepath":"\/redaxo\/include\/classes\/class.rex_form.inc.php","line_number":653},{"name":"REX_FORM_INPUT_CLASS","subject":"''","params":"array(\n  'form' => $this, \n  'inputType' => $inputType)","readonly":"","filepath":"\/redaxo\/include\/classes\/class.rex_form.inc.php","line_number":603},{"name":"REX_FORM_INPUT_TAG","subject":"''","params":"array(\n  'form' => $this, \n  'inputType' => $inputType)","readonly":"","filepath":"\/redaxo\/include\/classes\/class.rex_form.inc.php","line_number":631},{"name":"REX_FORM_SAVED","subject":"$saved","params":"array(\n  'form' => $this, \n  'sql' => $sql)","readonly":"","filepath":"\/redaxo\/include\/classes\/class.rex_form.inc.php","line_number":1027},{"name":"REX_LIST_CLASSNAME","subject":"'rex_list'","params":"array(\n  'query' => $query, \n  'rowsPerPage' => $rowsPerPage, \n  'listName' => $listName, \n  'debug' => $debug)","readonly":"","filepath":"\/redaxo\/include\/classes\/class.rex_list.inc.php","line_number":155},{"name":"REX_LIST_CLASSNAME","subject":"'rex_xform_list'","params":"array(\n  'query' => $query, \n  'rowsPerPage' => $rowsPerPage, \n  'listName' => $listName, \n  'debug' => $debug)","readonly":"","filepath":"\/redaxo\/include\/addons\/xform\/classes\/basic\/class.rex_xform_list.inc.php","line_number":24},{"name":"REX_MARKITUP_BUTTONS","subject":"array(\n  'buttondefinitions' => stripslashes($REX[\"rex_markitup\"][\"settings\"][\"buttondefinitions\"])","params":"'buttonsets' => stripslashes($REX[\"rex_markitup\"][\"settings\"][\"buttonsets\"]), \n  'buttoncss' => stripslashes($REX[\"rex_markitup\"][\"settings\"][\"buttoncss\"]), \n  'options' => stripslashes($REX[\"rex_markitup\"][\"settings\"][\"options\"]), \n  'immtypes' => $REX['ADDON']['image_manager']['types'])","readonly":"","filepath":"\/redaxo\/include\/addons\/rex_markitup\/config.inc.php","line_number":310},{"name":"REX_MARKITUP_IMAGE_TYPES_QUERY","subject":"'SELECT * FROM '.$REX['TABLE_PREFIX'].'679_types '.$REX['rex_markitup']['settings']['imm_sql_where'].' ORDER BY `name` ASC'","params":"","readonly":"","filepath":"\/redaxo\/include\/addons\/rex_markitup\/config.inc.php","line_number":288},{"name":"REX_NAVI_CLASSNAME","subject":"'nav42'","params":"","readonly":"","filepath":"\/redaxo\/include\/addons\/seo42\/classes\/class.nav42.inc.php","line_number":238},{"name":"REX_NAVI_CLASSNAME","subject":"'rex_navigation'","params":"","readonly":"","filepath":"\/redaxo\/include\/classes\/class.rex_navigation.inc.php","line_number":61},{"name":"REX_SQL_CLASSNAME","subject":"'rex_sql'","params":"array(\n  'DBID' => $DBID)","readonly":"","filepath":"\/redaxo\/include\/classes\/class.rex_sql.inc.php","line_number":848},{"name":"REX_XFORM_SAVED","subject":"$saved","params":"array(\n  'form' => $this, \n  'sql' => $sql, \n  'xform' => true)","readonly":"","filepath":"\/redaxo\/include\/addons\/xform\/classes\/action\/class.xform.action_db.inc.php","line_number":63},{"name":"SLICE_ADDED","subject":"$info","params":"array(\n  'article_id' => $article_id, \n  'clang' => $clang, \n  'function' => $function, \n  'mode' => $mode, \n  'slice_id' => $slice_id, \n  'page' => 'content', \n  'ctype' => $ctype, \n  'category_id' => $category_id, \n  'module_id' => $module_id, \n  'article_revision' => &$article_revision, \n  'slice_revision' => &$slice_revision)","readonly":"","filepath":"\/redaxo\/include\/pages\/content.inc.php","line_number":274},{"name":"SLICE_DELETED","subject":"$global_info","params":"array(\n  'article_id' => $article_id, \n  'clang' => $clang, \n  'function' => $function, \n  'mode' => $mode, \n  'slice_id' => $slice_id, \n  'page' => 'content', \n  'ctype' => $ctype, \n  'category_id' => $category_id, \n  'module_id' => $module_id, \n  'article_revision' => &$article_revision, \n  'slice_revision' => &$slice_revision)","readonly":"","filepath":"\/redaxo\/include\/pages\/content.inc.php","line_number":306},{"name":"SLICE_SHOW","subject":"$slice_content","params":"array(\n  'article_id' => $this->article_id, \n  'clang' => $this->clang, \n  'ctype' => $RE_CONTS_CTYPE[$I_ID], \n  'module_id' => $RE_MODUL_ID[$I_ID], \n  'slice_id' => $RE_CONTS[$I_ID], \n  'function' => $this->function, \n  'function_slice_id' => $this->slice_id)","readonly":"","filepath":"\/redaxo\/include\/classes\/class.rex_article_base.inc.php","line_number":311},{"name":"SLICE_UPDATED","subject":"$info","params":"array(\n  'article_id' => $article_id, \n  'clang' => $clang, \n  'function' => $function, \n  'mode' => $mode, \n  'slice_id' => $slice_id, \n  'page' => 'content', \n  'ctype' => $ctype, \n  'category_id' => $category_id, \n  'module_id' => $module_id, \n  'article_revision' => &$article_revision, \n  'slice_revision' => &$slice_revision)","readonly":"","filepath":"\/redaxo\/include\/pages\/content.inc.php","line_number":240},{"name":"URL_REWRITE","subject":"''","params":"array(\n  'id' => $id, \n  'name' => $name, \n  'clang' => $clang, \n  'params' => $param_string, \n  'divider' => $_divider)","readonly":"","filepath":"\/redaxo\/include\/functions\/function_rex_url.inc.php","line_number":106},{"name":"XFORM_DATA_ADD","subject":"$xform","params":"array(\n  'table' => $table)","readonly":"","filepath":"\/redaxo\/include\/addons\/xform\/plugins\/manager\/classes\/basic\/class.rex_xform_manager.inc.php","line_number":450},{"name":"XFORM_DATA_ADDED","subject":"$xform","params":"array(\n  'table' => $table)","readonly":"","filepath":"\/redaxo\/include\/addons\/xform\/plugins\/manager\/classes\/basic\/class.rex_xform_manager.inc.php","line_number":463},{"name":"XFORM_DATA_DATASET_DELETE","subject":"$delete","params":"array(\n  'table' => $table)","readonly":"","filepath":"\/redaxo\/include\/addons\/xform\/plugins\/manager\/classes\/basic\/class.rex_xform_manager.inc.php","line_number":259},{"name":"XFORM_DATA_DATASET_DELETED","subject":"''","params":"array(\n  'table' => $table)","readonly":"","filepath":"\/redaxo\/include\/addons\/xform\/plugins\/manager\/classes\/basic\/class.rex_xform_manager.inc.php","line_number":266},{"name":"XFORM_DATA_DELETE","subject":"$delete","params":"array(\n  'id' => $data_id, \n  'value' => $data, \n  'table' => $table)","readonly":"","filepath":"\/redaxo\/include\/addons\/xform\/plugins\/manager\/classes\/basic\/class.rex_xform_manager.inc.php","line_number":242},{"name":"XFORM_DATA_DELETED","subject":"''","params":"array(\n  'id' => $data_id, \n  'value' => $data, \n  'table' => $table)","readonly":"","filepath":"\/redaxo\/include\/addons\/xform\/plugins\/manager\/classes\/basic\/class.rex_xform_manager.inc.php","line_number":250},{"name":"XFORM_DATA_FORM","subject":"''","params":"array(\n  'form' => $form, \n  'func' => $func, \n  'this' => $this, \n  'table' => $table)","readonly":"","filepath":"\/redaxo\/include\/addons\/xform\/plugins\/manager\/classes\/basic\/class.rex_xform_manager.inc.php","line_number":489},{"name":"XFORM_DATA_LIST","subject":"$list","params":"array(\n  'table' => $table)","readonly":"","filepath":"\/redaxo\/include\/addons\/xform\/plugins\/manager\/classes\/basic\/class.rex_xform_manager.inc.php","line_number":712},{"name":"XFORM_DATA_LIST_SQL","subject":"$sql","params":"array(\n  'table' => $table)","readonly":"","filepath":"\/redaxo\/include\/addons\/xform\/plugins\/manager\/classes\/basic\/class.rex_xform_manager.inc.php","line_number":855},{"name":"XFORM_DATA_TABLE_TRUNCATE","subject":"$truncate","params":"array(\n  'table' => $table)","readonly":"","filepath":"\/redaxo\/include\/addons\/xform\/plugins\/manager\/classes\/basic\/class.rex_xform_manager.inc.php","line_number":273},{"name":"XFORM_DATA_TABLE_TRUNCATED","subject":"''","params":"array(\n  'table' => $table)","readonly":"","filepath":"\/redaxo\/include\/addons\/xform\/plugins\/manager\/classes\/basic\/class.rex_xform_manager.inc.php","line_number":280},{"name":"XFORM_DATA_UPDATE","subject":"$xform","params":"array(\n  'table' => $table, \n  'data_id' => $data_id, \n  'data' => $data)","readonly":"","filepath":"\/redaxo\/include\/addons\/xform\/plugins\/manager\/classes\/basic\/class.rex_xform_manager.inc.php","line_number":447},{"name":"XFORM_DATA_UPDATED","subject":"$xform","params":"array(\n  'table' => $table, \n  'data_id' => $data_id, \n  'data' => $data)","readonly":"","filepath":"\/redaxo\/include\/addons\/xform\/plugins\/manager\/classes\/basic\/class.rex_xform_manager.inc.php","line_number":459},{"name":"XFORM_EMAIL_BEFORE_REPLACEVARS","subject":"array(\n  'template' => $template, \n  'search_replace' => $er, \n  'status' => false)","params":"","readonly":"","filepath":"\/redaxo\/include\/addons\/xform\/plugins\/email\/classes\/basic\/class.rex_xform_emailtemplate.inc.php","line_number":27},{"name":"XFORM_EMAIL_BEFORE_SEND","subject":"array(\n  'template' => $template, \n  'template_name' => $template_name, \n  'status' => false)","params":"","readonly":"","filepath":"\/redaxo\/include\/addons\/xform\/plugins\/email\/classes\/basic\/class.rex_xform_emailtemplate.inc.php","line_number":67},{"name":"XFORM_EMAIL_SENT","subject":"$template_name","params":"$template","readonly":"true","filepath":"\/redaxo\/include\/addons\/xform\/plugins\/email\/classes\/basic\/class.rex_xform_emailtemplate.inc.php","line_number":102},{"name":"XFORM_EMAIL_SENT_FAILED","subject":"$template_name","params":"$template","readonly":"true","filepath":"\/redaxo\/include\/addons\/xform\/plugins\/email\/classes\/basic\/class.rex_xform_emailtemplate.inc.php","line_number":107},{"name":"XFORM_MANAGER_DATA_EDIT_FUNC","subject":"$show_editpage","params":"array(\n  'table' => $table, \n  'link_vars' => $this->getLinkVars())","readonly":"","filepath":"\/redaxo\/include\/addons\/xform\/plugins\/manager\/classes\/basic\/class.rex_xform_manager.inc.php","line_number":133},{"name":"XFORM_MANAGER_REX_INFO","subject":"''","params":"","readonly":"","filepath":"\/redaxo\/include\/addons\/xform\/plugins\/manager\/classes\/basic\/class.rex_xform_manager.inc.php","line_number":125},{"name":"XFORM_MANAGER_TABLE_FIELD_FUNC","subject":"$show_list","params":"array(\n  'table' => $t, \n  'link_vars' => $this->getLinkVars())","readonly":"","filepath":"\/redaxo\/include\/addons\/xform\/plugins\/manager\/classes\/basic\/class.rex_xform_manager.inc.php","line_number":1360}]