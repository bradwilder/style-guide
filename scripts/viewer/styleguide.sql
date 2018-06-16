select @section_id := id from sg_section where name = 'Colors';
update sg_section set enabled = 1 where id = @section_id;

insert into sg_color (name, hex, variant1, variant2) values ('Primary-1', 'F77C83', 'FB8D7E', 'EA768C');
set @primary1_id := last_insert_id();
insert into sg_color (name, hex, variant1, variant2) values ('Primary-2', 'E19FC5', 'E8A4C3', 'D999C9');
set @primary2_id := last_insert_id();
insert into sg_color (name, hex, variant1, variant2) values ('Primary-3', 'A67CC5', 'B977C2', '9A80C7');
set @primary3_id := last_insert_id();
insert into sg_color (name, hex) values ('Secondary-1', '262626');
set @secondary1_id := last_insert_id();
insert into sg_color (name, hex) values ('Secondary-2', '323232');
set @secondary2_id := last_insert_id();
insert into sg_color (name, hex) values ('Secondary-3', '2A2A2A');
set @secondary3_id := last_insert_id();
insert into sg_color (name, hex) values ('Secondary-4', '454545');
set @secondary4_id := last_insert_id();
insert into sg_color (name, hex) values ('Secondary-5', 'DADADA');
set @secondary5_id := last_insert_id();
insert into sg_color (name, hex) values ('Secondary-6', '8B8B8B');
set @secondary6_id := last_insert_id();
insert into sg_color (name, hex) values ('Secondary-7', '929999');
set @secondary7_id := last_insert_id();
insert into sg_color (name, hex) values ('Secondary-8', '4A4A4A');
set @secondary8_id := last_insert_id();

insert into sg_color_default (color_id) values (@secondary2_id);

insert into sg_subsection (name, description, sectionID, position) value ('Primary Colors', 'The main primary colors were taken from the palette found <a href="https://color.adobe.com/Color-Theme-1-color-theme-9633093/edit/?copy=true&base=2&rule=Custom&selected=4&name=Copy%20of%20Color%20Theme%201&mode=rgb&rgbvalues=0.9686274509803922,0.4862745098039216,0.5137254901959438,0.8823529411764706,0.623529411764706,0.7725490196078254,0.4666666666666051,0.6470588235294118,0.4392156862745098,0.5588118811881188,0.83,0.6492079207921203,0.6509803921569395,0.4862745098039215,0.7725490196078432&swatchOrder=0,1,2,3,4" target="_blank">here</a>.', @section_id, 1);
set @subsection_id := last_insert_id();

select @item_type_id := id from sg_item_type where code = 'color-var-desc';

insert into sg_item (name, typeID, colLg, colMd, colSm, colXs, subsectionID, position) values ('Main accent color swatch', @item_type_id, 4, 6, 12, 12, @subsection_id, 1);
set @item_id := last_insert_id();
insert into sg_color_item (baseID, color1ID) values (@item_id, @primary1_id);
insert into sg_color_descriptor (description, itemID, position) values ('Main accent color, with contextual variations.', @item_id, 1);
insert into sg_color_descriptor (description, itemID, position) values ('Indicates when elements are active or \'on\', or adds emphasis.', @item_id, 2);
insert into sg_color_descriptor (description, itemID, position) values ('Table highlighted color.', @item_id, 3);
insert into sg_color_descriptor (description, itemID, position) values ('Variant 2 for error messages.', @item_id, 4);
insert into sg_color_descriptor (description, itemID, position) values ('Variations: from <a href="http://paletton.com/#uid=55B070kfXvz6gR1bbGdkfr1osmP" target="_blank">Paletton</a> using adjacent colors with 7&deg; separation.', @item_id, 5);

insert into sg_item (name, typeID, colLg, colMd, colSm, colXs, subsectionID, position) values ('Main theme color swatch', @item_type_id, 4, 6, 12, 12, @subsection_id, 2);
set @item_id := last_insert_id();
insert into sg_color_item (baseID, color1ID) values (@item_id, @primary2_id);
insert into sg_color_descriptor (description, itemID, position) values ('Main theme color, with contextual variations.', @item_id, 1);
insert into sg_color_descriptor (description, itemID, position) values ('Table header main (unsorted) color.', @item_id, 2);
insert into sg_color_descriptor (description, itemID, position) values ('Variations: from <a href="http://paletton.com/#uid=559070k9pID1QYR5hSidVxciIri" target="_blank">Paletton</a> using adjacent colors with 7&deg; separation.', @item_id, 3);

insert into sg_item (name, typeID, colLg, colMd, colSm, colXs, subsectionID, position) values ('Main theme accent color swatch', @item_type_id, 4, 6, 12, 12, @subsection_id, 3);
set @item_id := last_insert_id();
insert into sg_color_item (baseID, color1ID) values (@item_id, @primary3_id);
insert into sg_color_descriptor (description, itemID, position) values ('Main theme accent color, with contextual variations.', @item_id, 1);
insert into sg_color_descriptor (description, itemID, position) values ('Table header sorted color.', @item_id, 2);
insert into sg_color_descriptor (description, itemID, position) values ('Variations: from <a href="http://paletton.com/#uid=54D0f0kcFET3EX583Phh8v4ljql" target="_blank">Paletton</a> using adjacent colors with 15&deg; separation.', @item_id, 3);

insert into sg_subsection (name, description, sectionID, position) value ('Secondary Colors', null, @section_id, 2);
set @subsection_id := last_insert_id();

select @item_type_id := id from sg_item_type where code = 'color-desc';

insert into sg_item (name, typeID, colLg, colMd, colSm, colXs, subsectionID, position) values ('Main background color tile', @item_type_id, 3, 6, 6, 12, @subsection_id, 1);
set @item_id := last_insert_id();
insert into sg_color_item (baseID, color1ID) values (@item_id, @secondary1_id);
insert into sg_color_descriptor (description, itemID, position) values ('Main background color.', @item_id, 1);

insert into sg_item (name, typeID, colLg, colMd, colSm, colXs, subsectionID, position) values ('Secondary background (high contrast) color tile', @item_type_id, 3, 6, 6, 12, @subsection_id, 2);
set @item_id := last_insert_id();
insert into sg_color_item (baseID, color1ID) values (@item_id, @secondary2_id);
insert into sg_color_descriptor (description, itemID, position) values ('Secondary background color.', @item_id, 1);
insert into sg_color_descriptor (description, itemID, position) values ('High contrast with main background color.', @item_id, 2);
insert into sg_color_descriptor (description, itemID, position) values ('Groups elements or gives emphasis.', @item_id, 3);
insert into sg_color_descriptor (description, itemID, position) values ('Filter background color.', @item_id, 4);
insert into sg_color_descriptor (description, itemID, position) values ('Disabled background color.', @item_id, 5);

insert into sg_item (name, typeID, colLg, colMd, colSm, colXs, subsectionID, position) values ('Secondary background (low contrast) color tile', @item_type_id, 3, 6, 6, 12, @subsection_id, 3);
set @item_id := last_insert_id();
insert into sg_color_item (baseID, color1ID) values (@item_id, @secondary3_id);
insert into sg_color_descriptor (description, itemID, position) values ('Secondary background color.', @item_id, 1);
insert into sg_color_descriptor (description, itemID, position) values ('Low contrast with main background color.', @item_id, 2);
insert into sg_color_descriptor (description, itemID, position) values ('Groups elements or gives emphasis.', @item_id, 3);
insert into sg_color_descriptor (description, itemID, position) values ('Light background text color.', @item_id, 4);
insert into sg_color_descriptor (description, itemID, position) values ('Filter background alternative color.', @item_id, 5);

insert into sg_item (name, typeID, colLg, colMd, colSm, colXs, subsectionID, position) values ('Main definition color tile', @item_type_id, 3, 6, 6, 12, @subsection_id, 4);
set @item_id := last_insert_id();
insert into sg_color_item (baseID, color1ID) values (@item_id, @secondary4_id);
insert into sg_color_descriptor (description, itemID, position) values ('Main definition color.', @item_id, 1);
insert into sg_color_descriptor (description, itemID, position) values ('Provides subtle emphasis or definition for elements.', @item_id, 2);
insert into sg_color_descriptor (description, itemID, position) values ('Filter definition color.', @item_id, 3);

insert into sg_item (name, typeID, colLg, colMd, colSm, colXs, subsectionID, position) values ('Enabled text color tile', @item_type_id, 3, 6, 6, 12, @subsection_id, 5);
set @item_id := last_insert_id();
insert into sg_color_item (baseID, color1ID) values (@item_id, @secondary5_id);
insert into sg_color_descriptor (description, itemID, position) values ('Enabled text color.', @item_id, 1);
insert into sg_color_descriptor (description, itemID, position) values ('Main dark background text color.', @item_id, 2);
insert into sg_color_descriptor (description, itemID, position) values ('Table striping color.', @item_id, 3);
insert into sg_color_descriptor (description, itemID, position) values ('Hover color.', @item_id, 4);

insert into sg_item (name, typeID, colLg, colMd, colSm, colXs, subsectionID, position) values ('Disabled text color tile', @item_type_id, 3, 6, 6, 12, @subsection_id, 6);
set @item_id := last_insert_id();
insert into sg_color_item (baseID, color1ID) values (@item_id, @secondary6_id);
insert into sg_color_descriptor (description, itemID, position) values ('Disabled text color.', @item_id, 1);

insert into sg_item (name, typeID, colLg, colMd, colSm, colXs, subsectionID, position) values ('Inactive text color tile', @item_type_id, 3, 6, 6, 12, @subsection_id, 7);
set @item_id := last_insert_id();
insert into sg_color_item (baseID, color1ID) values (@item_id, @secondary7_id);
insert into sg_color_descriptor (description, itemID, position) values ('Inactive text color.', @item_id, 1);

insert into sg_item (name, typeID, colLg, colMd, colSm, colXs, subsectionID, position) values ('Highlighted row color tile', @item_type_id, 3, 6, 6, 12, @subsection_id, 8);
set @item_id := last_insert_id();
insert into sg_color_item (baseID, color1ID) values (@item_id, @secondary8_id);
insert into sg_color_descriptor (description, itemID, position) values ('Highlighted row color.', @item_id, 1);


select @section_id := id from sg_section where name = 'Typography';
update sg_section set enabled = 1 where id = @section_id;

select @web_font_type_id := id from sg_font_type where code = 'web';

select @cyr_greek_font_alphabet_id := id from sg_font_alphabet where code = 'cyrgrk';

insert into sg_font (name, typeID, alphabetID) values ('Source Sans Pro', @web_font_type_id, @cyr_greek_font_alphabet_id);
set @source_sans_pro_id := last_insert_id();
insert into sg_webfont (baseID, importURL, website) values (@source_sans_pro_id, 'https://fonts.googleapis.com/css?family=Source+Sans+Pro:100,200,300,400,500,600,700,800,900', 'https://fonts.google.com/specimen/Source+Sans+Pro');

select @std_font_alphabet_id := id from sg_font_alphabet where code = 'std';

insert into sg_font (name, typeID, alphabetID) values ('Droid Sans Mono', @web_font_type_id, @std_font_alphabet_id);
set @droid_sans_mono_id := last_insert_id();
insert into sg_webfont (baseID, importURL, website) values (@droid_sans_mono_id, 'https://fonts.googleapis.com/css?family=Droid+Sans+Mono', 'https://fonts.google.com/specimen/Droid+Sans+Mono');

select @css_font_type_id := id from sg_font_type where code = 'css';

insert into sg_font (name, typeID) values ('Font Awesome', @css_font_type_id);
set @font_awesome_id := last_insert_id();
insert into sg_cssfont (baseID, directory, cssFile) values (@font_awesome_id, 'font-awesome-4.7.0', 'font-awesome-4.7.0/css/font-awesome.min.css');

insert into sg_subsection (name, description, sectionID, position) value ('Modular Scale', 'The modular scale ratio is 1.25 (5:4), with a base of 16px (1em). The scale can be viewed <a href="http://www.modularscale.com/?16&px&1.25" target="_blank">here</a>.', @section_id, 1);

insert into sg_subsection (name, description, sectionID, position) value ('Font Families', null, @section_id, 2);
set @subsection_id := last_insert_id();

select @item_type_id := id from sg_item_type where code = 'font-fmy';

insert into sg_item (name, typeID, colLg, colMd, colSm, colXs, subsectionID, position) values ('Source Sans Pro font family card', @item_type_id, 6, 6, 12, 12, @subsection_id, 1);
set @item_id := last_insert_id();
insert into sg_font_family (fontID, baseID) values (@source_sans_pro_id, @item_id);

insert into sg_item (name, typeID, colLg, colMd, colSm, colXs, subsectionID, position) values ('Droid Sans Mono font family card', @item_type_id, 6, 6, 12, 12, @subsection_id, 2);
set @item_id := last_insert_id();
insert into sg_font_family (fontID, baseID) values (@droid_sans_mono_id, @item_id);

insert into sg_subsection (name, description, sectionID, position) value ('Font Sizes', 'This table illustrates typical font sizes and usages.', @section_id, 3);
set @subsection_id := last_insert_id();

select @item_type_id := id from sg_item_type where code = 'font-tbl';

insert into sg_item (name, typeID, colLg, colMd, colSm, colXs, subsectionID, position) values ('Main font usage table', @item_type_id, 12, 12, 12, 12, @subsection_id, 1);
set @item_id := last_insert_id();
insert into sg_font_listing_table (baseID) values (@item_id);

insert into sg_font_listing (text, itemID, fontID, position) values ('Heading 1', @item_id, @source_sans_pro_id, 1);
set @listing_id := last_insert_id();
insert into sg_font_listing_css (css, fontListingID) values ('font-size: 39.06px;', @listing_id);
insert into sg_font_listing_css (css, fontListingID) values ('font-weight: normal;', @listing_id);
insert into sg_font_listing_css (css, fontListingID) values ('line-height: 1.1;', @listing_id);

insert into sg_font_listing (text, itemID, fontID, position) values ('Heading 2', @item_id, @source_sans_pro_id, 2);
set @listing_id := last_insert_id();
insert into sg_font_listing_css (css, fontListingID) values ('font-size: 31.25px;', @listing_id);
insert into sg_font_listing_css (css, fontListingID) values ('font-weight: lighter;', @listing_id);
insert into sg_font_listing_css (css, fontListingID) values ('line-height: 1.1;', @listing_id);

insert into sg_font_listing (text, itemID, fontID, position) values ('Heading 3', @item_id, @source_sans_pro_id, 3);
set @listing_id := last_insert_id();
insert into sg_font_listing_css (css, fontListingID) values ('font-size: 25px;', @listing_id);
insert into sg_font_listing_css (css, fontListingID) values ('font-weight: bold;', @listing_id);
insert into sg_font_listing_css (css, fontListingID) values ('line-height: 1.1;', @listing_id);

insert into sg_font_listing (text, itemID, fontID, position) values ('Heading 4', @item_id, @source_sans_pro_id, 4);
set @listing_id := last_insert_id();
insert into sg_font_listing_css (css, fontListingID) values ('font-size: 20px;', @listing_id);
insert into sg_font_listing_css (css, fontListingID) values ('font-weight: lighter;', @listing_id);
insert into sg_font_listing_css (css, fontListingID) values ('line-height: 1.1;', @listing_id);
insert into sg_font_listing_css (css, fontListingID) values ('letter-spacing: 2px;', @listing_id);
insert into sg_font_listing_css (css, fontListingID) values ('text-transform: uppercase;', @listing_id);

insert into sg_font_listing (text, itemID, fontID, position) values ('Heading 5', @item_id, @source_sans_pro_id, 5);
set @listing_id := last_insert_id();
insert into sg_font_listing_css (css, fontListingID) values ('font-size: 16px;', @listing_id);
insert into sg_font_listing_css (css, fontListingID) values ('font-weight: lighter;', @listing_id);
insert into sg_font_listing_css (css, fontListingID) values ('line-height: 1.1;', @listing_id);
insert into sg_font_listing_css (css, fontListingID) values ('letter-spacing: 2px;', @listing_id);
insert into sg_font_listing_css (css, fontListingID) values ('text-transform: uppercase;', @listing_id);

insert into sg_font_listing (text, itemID, fontID, position) values ('Heading 6', @item_id, @source_sans_pro_id, 6);
set @listing_id := last_insert_id();
insert into sg_font_listing_css (css, fontListingID) values ('font-size: 12.8px;', @listing_id);
insert into sg_font_listing_css (css, fontListingID) values ('font-weight: lighter;', @listing_id);
insert into sg_font_listing_css (css, fontListingID) values ('line-height: 1.1;', @listing_id);
insert into sg_font_listing_css (css, fontListingID) values ('letter-spacing: 2px;', @listing_id);
insert into sg_font_listing_css (css, fontListingID) values ('text-transform: uppercase;', @listing_id);

insert into sg_font_listing (text, itemID, fontID, position) values ('Body text. Donec sed odio dui. Nulla vitae elit libero, a pharetra augue. Vestibulum id ligula porta felis euismod semper. Lorem ipsum dolor sit amet, consectetur adipiscing elit.', @item_id, @source_sans_pro_id, 7);
set @listing_id := last_insert_id();
insert into sg_font_listing_css (css, fontListingID) values ('font-size: 12.8px;', @listing_id);
insert into sg_font_listing_css (css, fontListingID) values ('font-weight: normal;', @listing_id);
insert into sg_font_listing_css (css, fontListingID) values ('line-height: 1.3;', @listing_id);

insert into sg_font_listing (text, itemID, fontID, position) values ('Table header', @item_id, @droid_sans_mono_id, 8);
set @listing_id := last_insert_id();
insert into sg_font_listing_css (css, fontListingID) values ('font-size: 8px;', @listing_id);
insert into sg_font_listing_css (css, fontListingID) values ('font-weight: bold;', @listing_id);
insert into sg_font_listing_css (css, fontListingID) values ('line-height: 1;', @listing_id);

insert into sg_font_listing (text, itemID, fontID, position) values ('Table data', @item_id, @droid_sans_mono_id, 9);
set @listing_id := last_insert_id();
insert into sg_font_listing_css (css, fontListingID) values ('font-size: 9.3px;', @listing_id);
insert into sg_font_listing_css (css, fontListingID) values ('font-weight: normal;', @listing_id);
insert into sg_font_listing_css (css, fontListingID) values ('line-height: 1;', @listing_id);


select @section_id := id from sg_section where name = 'Elements';
update sg_section set enabled = 1 where id = @section_id;

insert into sg_subsection (name, description, sectionID, position) value ('Icons', 'The icons are taken from the <a href="http://fontawesome.io/" target="_blank">Font Awesome</a> set.', @section_id, 1);
set @subsection_id := last_insert_id();

select @item_type_id := id from sg_item_type where code = 'icons-css';

insert into sg_subsection (name, description, sectionID, position, parentSubsectionID) value ('Tables', null, @section_id, 1, @subsection_id);
set @subsubsection_id := last_insert_id();

insert into sg_item (name, typeID, colLg, colMd, colSm, colXs, subsectionID, position) values ('Tables icon listing', @item_type_id, 12, 12, 12, 12, @subsubsection_id, 1);
set @item_id := last_insert_id();
insert into sg_icon_listing_table (baseID, fontID) values (@item_id, @font_awesome_id);

insert into sg_icon_listing (html, itemID, position) values ('<i class="fa fa-sort" aria-hidden="true"></i>', @item_id, 1);
insert into sg_icon_listing (html, itemID, position) values ('<i class="fa fa-sort-asc" aria-hidden="true"></i>', @item_id, 2);
insert into sg_icon_listing (html, itemID, position) values ('<i class="fa fa-sort-desc" aria-hidden="true"></i>', @item_id, 3);

insert into sg_subsection (name, description, sectionID, position, parentSubsectionID) value ('Misc', null, @section_id, 2, @subsection_id);
set @subsubsection_id := last_insert_id();

insert into sg_item (name, typeID, colLg, colMd, colSm, colXs, subsectionID, position) values ('Misc icon listing', @item_type_id, 12, 12, 12, 12, @subsubsection_id, 1);
set @item_id := last_insert_id();
insert into sg_icon_listing_table (baseID, fontID) values (@item_id, @font_awesome_id);

insert into sg_icon_listing (html, itemID, position) values ('<i class="fa fa-bar-chart" aria-hidden="true"></i>', @item_id, 1);
insert into sg_icon_listing (html, itemID, position) values ('<i class="fa fa-user" aria-hidden="true"></i>', @item_id, 2);
insert into sg_icon_listing (html, itemID, position) values ('<i class="fa fa-pencil" aria-hidden="true"></i>', @item_id, 3);
insert into sg_icon_listing (html, itemID, position) values ('<i class="fa fa-plus" aria-hidden="true"></i>', @item_id, 4);
insert into sg_icon_listing (html, itemID, position) values ('<i class="fa fa-angle-up" aria-hidden="true"></i>', @item_id, 5);
insert into sg_icon_listing (html, itemID, position) values ('<i class="fa fa-angle-down" aria-hidden="true"></i>', @item_id, 6);
insert into sg_icon_listing (html, itemID, position) values ('<i class="fa fa-close" aria-hidden="true"></i>', @item_id, 7);
insert into sg_icon_listing (html, itemID, position) values ('<i class="fa fa-check" aria-hidden="true"></i>', @item_id, 8);
insert into sg_icon_listing (html, itemID, position) values ('<i class="fa fa-minus" aria-hidden="true"></i>', @item_id, 9);
insert into sg_icon_listing (html, itemID, position) values ('<i class="fa fa-exclamation" aria-hidden="true"></i>', @item_id, 10);
insert into sg_icon_listing (html, itemID, position) values ('<i class="fa fa-star" aria-hidden="true"></i>', @item_id, 11);

select @elements_upload_id := id from sg_upload where filePath = 'elements' and parentID is null;
select @folder_type_id := id from sg_upload_type where code = 'folder';
select @file_type_id := id from sg_upload_type where code = 'file';

insert into sg_upload (filePath, parentID, typeID) values ('alerts', @elements_upload_id, @folder_type_id);
set @alerts_upload_id := last_insert_id();

insert into sg_upload (filePath, parentID, typeID) values ('buttons', @elements_upload_id, @folder_type_id);
set @buttons_upload_id := last_insert_id();

insert into sg_upload (filePath, parentID, typeID) values ('checkboxes', @elements_upload_id, @folder_type_id);
set @checkboxes_upload_id := last_insert_id();

insert into sg_upload (filePath, parentID, typeID) values ('dropdowns', @elements_upload_id, @folder_type_id);
set @dropdowns_upload_id := last_insert_id();

insert into sg_upload (filePath, parentID, typeID) values ('radio-buttons', @elements_upload_id, @folder_type_id);
set @radio_buttons_upload_id := last_insert_id();

insert into sg_upload (filePath, parentID, typeID) values ('ratings', @elements_upload_id, @folder_type_id);
set @ratings_upload_id := last_insert_id();

insert into sg_upload (filePath, parentID, typeID) values ('scrollbars', @elements_upload_id, @folder_type_id);
set @scrollbars_upload_id := last_insert_id();

insert into sg_upload (filePath, parentID, typeID) values ('sliders', @elements_upload_id, @folder_type_id);
set @sliders_upload_id := last_insert_id();

insert into sg_upload (filePath, parentID, typeID) values ('textboxes', @elements_upload_id, @folder_type_id);
set @textboxes_upload_id := last_insert_id();

insert into sg_upload (filePath, parentID, typeID) values ('time-inputs', @elements_upload_id, @folder_type_id);
set @time_inputs_upload_id := last_insert_id();

insert into sg_upload (filePath, parentID, typeID) values ('toggle-switches', @elements_upload_id, @folder_type_id);
set @toggle_switches_upload_id := last_insert_id();

insert into sg_upload (parentID, filePath, typeID) values (@alerts_upload_id, 'Error.png', @file_type_id);
set @alert_error_id := last_insert_id();
insert into sg_upload_file (baseID, shortName, fullName) values (@alert_error_id, 'Error', 'Alert Error');

insert into sg_upload (parentID, filePath, typeID) values (@alerts_upload_id, 'Success.png', @file_type_id);
set @alert_success_id := last_insert_id();
insert into sg_upload_file (baseID, shortName, fullName) values (@alert_success_id, 'Success', 'Alert Success');

insert into sg_upload (parentID, filePath, typeID) values (@buttons_upload_id, 'Active-accent.png', @file_type_id);
set @button_active_accent_id := last_insert_id();
insert into sg_upload_file (baseID, shortName, fullName) values (@button_active_accent_id, 'Active', 'Button Active');

insert into sg_upload (parentID, filePath, typeID) values (@buttons_upload_id, 'Active-dark-accent.png', @file_type_id);
set @button_active_dark_accent_id := last_insert_id();
insert into sg_upload_file (baseID, shortName, fullName) values (@button_active_dark_accent_id, 'Active Dark', 'Button Active Dark');

insert into sg_upload (parentID, filePath, typeID) values (@buttons_upload_id, 'Active-dark-main.png', @file_type_id);
set @button_active_dark_main_id := last_insert_id();
insert into sg_upload_file (baseID, shortName, fullName) values (@button_active_dark_main_id, 'Active Dark', 'Button Active Dark');

insert into sg_upload (parentID, filePath, typeID) values (@buttons_upload_id, 'Active-main.png', @file_type_id);
set @button_active_main_id := last_insert_id();
insert into sg_upload_file (baseID, shortName, fullName) values (@button_active_main_id, 'Active', 'Button Active');

insert into sg_upload (parentID, filePath, typeID) values (@buttons_upload_id, 'Disabled-accent.png', @file_type_id);
set @button_disabled_accent_id := last_insert_id();
insert into sg_upload_file (baseID, shortName, fullName) values (@button_disabled_accent_id, 'Disabled', 'Button Disabled');

insert into sg_upload (parentID, filePath, typeID) values (@buttons_upload_id, 'Disabled-dark-accent.png', @file_type_id);
set @button_disabled_dark_accent_id := last_insert_id();
insert into sg_upload_file (baseID, shortName, fullName) values (@button_disabled_dark_accent_id, 'Disabled Dark', 'Button Disabled Dark');

insert into sg_upload (parentID, filePath, typeID) values (@buttons_upload_id, 'Disabled-dark-main.png', @file_type_id);
set @button_disabled_dark_main_id := last_insert_id();
insert into sg_upload_file (baseID, shortName, fullName) values (@button_disabled_dark_main_id, 'Disabled Dark', 'Button Disabled Dark');

insert into sg_upload (parentID, filePath, typeID) values (@buttons_upload_id, 'Disabled-main.png', @file_type_id);
set @button_disabled_main_id := last_insert_id();
insert into sg_upload_file (baseID, shortName, fullName) values (@button_disabled_main_id, 'Disabled', 'Button Disabled');

insert into sg_upload (parentID, filePath, typeID) values (@buttons_upload_id, 'Hover-accent.png', @file_type_id);
set @button_hover_accent_id := last_insert_id();
insert into sg_upload_file (baseID, shortName, fullName) values (@button_hover_accent_id, 'Hover', 'Button Hover');

insert into sg_upload (parentID, filePath, typeID) values (@buttons_upload_id, 'Hover-dark-accent.png', @file_type_id);
set @button_hover_dark_accent_id := last_insert_id();
insert into sg_upload_file (baseID, shortName, fullName) values (@button_hover_dark_accent_id, 'Hover Dark', 'Button Hover Dark');

insert into sg_upload (parentID, filePath, typeID) values (@buttons_upload_id, 'Hover-dark-main.png', @file_type_id);
set @button_hover_dark_main_id := last_insert_id();
insert into sg_upload_file (baseID, shortName, fullName) values (@button_hover_dark_main_id, 'Hover Dark', 'Button Hover Dark');

insert into sg_upload (parentID, filePath, typeID) values (@buttons_upload_id, 'Hover-main.png', @file_type_id);
set @button_hover_main_id := last_insert_id();
insert into sg_upload_file (baseID, shortName, fullName) values (@button_hover_main_id, 'Hover', 'Button Hover');

insert into sg_upload (parentID, filePath, typeID) values (@buttons_upload_id, 'Normal-accent.png', @file_type_id);
set @button_normal_accent_id := last_insert_id();
insert into sg_upload_file (baseID, shortName, fullName) values (@button_normal_accent_id, 'Normal', 'Button Normal');

insert into sg_upload (parentID, filePath, typeID) values (@buttons_upload_id, 'Normal-dark-accent.png', @file_type_id);
set @button_normal_dark_accent_id := last_insert_id();
insert into sg_upload_file (baseID, shortName, fullName) values (@button_normal_dark_accent_id, 'Normal Dark', 'Button Normal Dark');

insert into sg_upload (parentID, filePath, typeID) values (@buttons_upload_id, 'Normal-dark-main.png', @file_type_id);
set @button_normal_dark_main_id := last_insert_id();
insert into sg_upload_file (baseID, shortName, fullName) values (@button_normal_dark_main_id, 'Normal Dark', 'Button Normal Dark');

insert into sg_upload (parentID, filePath, typeID) values (@buttons_upload_id, 'Normal-main.png', @file_type_id);
set @button_normal_main_id := last_insert_id();
insert into sg_upload_file (baseID, shortName, fullName) values (@button_normal_main_id, 'Normal', 'Button Normal');

insert into sg_upload (parentID, filePath, typeID) values (@checkboxes_upload_id, 'Checked-ternary.png', @file_type_id);
set @checkbox_checked_ternary_id := last_insert_id();
insert into sg_upload_file (baseID, shortName, fullName) values (@checkbox_checked_ternary_id, 'Alt Checked', 'Checkbox Alt Checked');

insert into sg_upload (parentID, filePath, typeID) values (@checkboxes_upload_id, 'Checked.png', @file_type_id);
set @checkbox_checked_id := last_insert_id();
insert into sg_upload_file (baseID, shortName, fullName) values (@checkbox_checked_id, 'Checked', 'Checkbox Checked');

insert into sg_upload (parentID, filePath, typeID) values (@checkboxes_upload_id, 'Disabled.png', @file_type_id);
set @checkbox_disabled_id := last_insert_id();
insert into sg_upload_file (baseID, shortName, fullName) values (@checkbox_disabled_id, 'Disabled', 'Checkbox Disabled');

insert into sg_upload (parentID, filePath, typeID) values (@checkboxes_upload_id, 'Hover.png', @file_type_id);
set @checkbox_hover_id := last_insert_id();
insert into sg_upload_file (baseID, shortName, fullName) values (@checkbox_hover_id, 'Hover', 'Checkbox Hover');

insert into sg_upload (parentID, filePath, typeID) values (@checkboxes_upload_id, 'Unchecked.png', @file_type_id);
set @checkbox_unchecked_id := last_insert_id();
insert into sg_upload_file (baseID, shortName, fullName) values (@checkbox_unchecked_id, 'Unchecked', 'Checkbox Unchecked');

insert into sg_upload (parentID, filePath, typeID) values (@dropdowns_upload_id, 'Closed.png', @file_type_id);
set @dropdown_closed_id := last_insert_id();
insert into sg_upload_file (baseID, shortName, fullName) values (@dropdown_closed_id, 'Closed', 'Dropdown Closed');

insert into sg_upload (parentID, filePath, typeID) values (@dropdowns_upload_id, 'Disabled.png', @file_type_id);
set @dropdown_disabled_id := last_insert_id();
insert into sg_upload_file (baseID, shortName, fullName) values (@dropdown_disabled_id, 'Disabled', 'Dropdown Disabled');

insert into sg_upload (parentID, filePath, typeID) values (@dropdowns_upload_id, 'Hover.png', @file_type_id);
set @dropdown_hover_id := last_insert_id();
insert into sg_upload_file (baseID, shortName, fullName) values (@dropdown_hover_id, 'Hover', 'Dropdown Hover');

insert into sg_upload (parentID, filePath, typeID) values (@dropdowns_upload_id, 'Multi-select-closed.png', @file_type_id);
set @dropdown_multi_closed_id := last_insert_id();
insert into sg_upload_file (baseID, shortName, fullName) values (@dropdown_multi_closed_id, 'Multi-Select Closed', 'Dropdown Multi-Select Closed');

insert into sg_upload (parentID, filePath, typeID) values (@dropdowns_upload_id, 'Multi-select-open.png', @file_type_id);
set @dropdown_multi_open_id := last_insert_id();
insert into sg_upload_file (baseID, shortName, fullName) values (@dropdown_multi_open_id, 'Multi-Select Open', 'Dropdown Multi-Select Open');

insert into sg_upload (parentID, filePath, typeID) values (@dropdowns_upload_id, 'Open-Up.png', @file_type_id);
set @dropdown_open_up_id := last_insert_id();
insert into sg_upload_file (baseID, shortName, fullName) values (@dropdown_open_up_id, 'Open Up', 'Dropdown Open Up');

insert into sg_upload (parentID, filePath, typeID) values (@dropdowns_upload_id, 'Open.png', @file_type_id);
set @dropdown_open_id := last_insert_id();
insert into sg_upload_file (baseID, shortName, fullName) values (@dropdown_open_id, 'Open', 'Dropdown Open');

insert into sg_upload (parentID, filePath, typeID) values (@radio_buttons_upload_id, 'Disabled.png', @file_type_id);
set @radio_disabled_id := last_insert_id();
insert into sg_upload_file (baseID, shortName, fullName) values (@radio_disabled_id, 'Disabled', 'Radio Disabled');

insert into sg_upload (parentID, filePath, typeID) values (@radio_buttons_upload_id, 'Hover.png', @file_type_id);
set @radio_hover_id := last_insert_id();
insert into sg_upload_file (baseID, shortName, fullName) values (@radio_hover_id, 'Hover', 'Radio Hover');

insert into sg_upload (parentID, filePath, typeID) values (@radio_buttons_upload_id, 'Selected.png', @file_type_id);
set @radio_selected_id := last_insert_id();
insert into sg_upload_file (baseID, shortName, fullName) values (@radio_selected_id, 'Selected', 'Radio Selected');

insert into sg_upload (parentID, filePath, typeID) values (@radio_buttons_upload_id, 'Unselected.png', @file_type_id);
set @radio_unselected_id := last_insert_id();
insert into sg_upload_file (baseID, shortName, fullName) values (@radio_unselected_id, 'Unselected', 'Radio Unselected');

insert into sg_upload (parentID, filePath, typeID) values (@ratings_upload_id, 'Disabled.png', @file_type_id);
set @rating_disabled_id := last_insert_id();
insert into sg_upload_file (baseID, shortName, fullName) values (@rating_disabled_id, 'Disabled', 'Rating Disabled');

insert into sg_upload (parentID, filePath, typeID) values (@ratings_upload_id, 'Enabled.png', @file_type_id);
set @rating_enabled_id := last_insert_id();
insert into sg_upload_file (baseID, shortName, fullName) values (@rating_enabled_id, 'Enabled', 'Rating Enabled');

insert into sg_upload (parentID, filePath, typeID) values (@scrollbars_upload_id, 'Disabled.png', @file_type_id);
set @scrollbar_disabled_id := last_insert_id();
insert into sg_upload_file (baseID, shortName, fullName) values (@scrollbar_disabled_id, 'Disabled', 'Scrollbar Disabled');

insert into sg_upload (parentID, filePath, typeID) values (@scrollbars_upload_id, 'Enabled.png', @file_type_id);
set @scrollbar_enabled_id := last_insert_id();
insert into sg_upload_file (baseID, shortName, fullName) values (@scrollbar_enabled_id, 'Enabled', 'Scrollbar Enabled');

insert into sg_upload (parentID, filePath, typeID) values (@scrollbars_upload_id, 'Hover.png', @file_type_id);
set @scrollbar_hover_id := last_insert_id();
insert into sg_upload_file (baseID, shortName, fullName) values (@scrollbar_hover_id, 'Hover', 'Scrollbar Hover');

insert into sg_upload (parentID, filePath, typeID) values (@scrollbars_upload_id, 'Light.png', @file_type_id);
set @scrollbar_light_id := last_insert_id();
insert into sg_upload_file (baseID, shortName, fullName) values (@scrollbar_light_id, 'Light', 'Scrollbar Light');

insert into sg_upload (parentID, filePath, typeID) values (@sliders_upload_id, 'Disabled.png', @file_type_id);
set @slider_disabled_id := last_insert_id();
insert into sg_upload_file (baseID, shortName, fullName) values (@slider_disabled_id, 'Disabled', 'Slider Disabled');

insert into sg_upload (parentID, filePath, typeID) values (@sliders_upload_id, 'Enabled.png', @file_type_id);
set @slider_enabled_id := last_insert_id();
insert into sg_upload_file (baseID, shortName, fullName) values (@slider_enabled_id, 'Enabled', 'Slider Enabled');

insert into sg_upload (parentID, filePath, typeID) values (@sliders_upload_id, 'Hover.png', @file_type_id);
set @slider_hover_id := last_insert_id();
insert into sg_upload_file (baseID, shortName, fullName) values (@slider_hover_id, 'Hover', 'Slider Hover');

insert into sg_upload (parentID, filePath, typeID) values (@sliders_upload_id, 'Two-input-disabled.png', @file_type_id);
set @slider_two_input_disabled_id := last_insert_id();
insert into sg_upload_file (baseID, shortName, fullName) values (@slider_two_input_disabled_id, 'Two-Input Disabled', 'Slider Two-Input Disabled');

insert into sg_upload (parentID, filePath, typeID) values (@sliders_upload_id, 'Two-input-enabled.png', @file_type_id);
set @slider_two_input_enabled_id := last_insert_id();
insert into sg_upload_file (baseID, shortName, fullName) values (@slider_two_input_enabled_id, 'Two-Input Enabled', 'Slider Two-Input Enabled');

insert into sg_upload (parentID, filePath, typeID) values (@textboxes_upload_id, 'Disabled.png', @file_type_id);
set @textbox_disabled_id := last_insert_id();
insert into sg_upload_file (baseID, shortName, fullName) values (@textbox_disabled_id, 'Disabled', 'Textbox Disabled');

insert into sg_upload (parentID, filePath, typeID) values (@textboxes_upload_id, 'Enabled.png', @file_type_id);
set @textbox_enabled_id := last_insert_id();
insert into sg_upload_file (baseID, shortName, fullName) values (@textbox_enabled_id, 'Enabled', 'Textbox Enabled');

insert into sg_upload (parentID, filePath, typeID) values (@textboxes_upload_id, 'Error.png', @file_type_id);
set @textbox_error_id := last_insert_id();
insert into sg_upload_file (baseID, shortName, fullName) values (@textbox_error_id, 'Error', 'Textbox Error');

insert into sg_upload (parentID, filePath, typeID) values (@textboxes_upload_id, 'Hover.png', @file_type_id);
set @textbox_hover_id := last_insert_id();
insert into sg_upload_file (baseID, shortName, fullName) values (@textbox_hover_id, 'Hover', 'Textbox Hover');

insert into sg_upload (parentID, filePath, typeID) values (@textboxes_upload_id, 'Placeholder.png', @file_type_id);
set @textbox_placeholder_id := last_insert_id();
insert into sg_upload_file (baseID, shortName, fullName) values (@textbox_placeholder_id, 'Placeholder', 'Textbox Placeholder');

insert into sg_upload (parentID, filePath, typeID) values (@time_inputs_upload_id, 'Disabled.png', @file_type_id);
set @time_disabled_id := last_insert_id();
insert into sg_upload_file (baseID, shortName, fullName) values (@time_disabled_id, 'Disabled', 'Time Input Disabled');

insert into sg_upload (parentID, filePath, typeID) values (@time_inputs_upload_id, 'Enabled.png', @file_type_id);
set @time_enabled_id := last_insert_id();
insert into sg_upload_file (baseID, shortName, fullName) values (@time_enabled_id, 'Enabled', 'Time Input Enabled');

insert into sg_upload (parentID, filePath, typeID) values (@time_inputs_upload_id, 'Hover.png', @file_type_id);
set @time_hover_id := last_insert_id();
insert into sg_upload_file (baseID, shortName, fullName) values (@time_hover_id, 'Hover', 'Time Input Hover');

insert into sg_upload (parentID, filePath, typeID) values (@toggle_switches_upload_id, 'Disabled.png', @file_type_id);
set @toggle_disabled_id := last_insert_id();
insert into sg_upload_file (baseID, shortName, fullName) values (@toggle_disabled_id, 'Disabled', 'Toggle Switch Disabled');

insert into sg_upload (parentID, filePath, typeID) values (@toggle_switches_upload_id, 'Hover.png', @file_type_id);
set @toggle_hover_id := last_insert_id();
insert into sg_upload_file (baseID, shortName, fullName) values (@toggle_hover_id, 'Hover', 'Toggle Switch Hover');

insert into sg_upload (parentID, filePath, typeID) values (@toggle_switches_upload_id, 'On.png', @file_type_id);
set @toggle_on_id := last_insert_id();
insert into sg_upload_file (baseID, shortName, fullName) values (@toggle_on_id, 'On', 'Toggle Switch On');

insert into sg_upload (parentID, filePath, typeID) values (@toggle_switches_upload_id, 'Off.png', @file_type_id);
set @toggle_off_id := last_insert_id();
insert into sg_upload_file (baseID, shortName, fullName) values (@toggle_off_id, 'Off', 'Toggle Switch Off');

insert into sg_subsection (name, description, sectionID, position) value ('Checkboxes', null, @section_id, 2);
set @subsection_id := last_insert_id();

select @item_type_id := id from sg_item_type where code = 'elem-seg';

insert into sg_item (name, typeID, colLg, colMd, colSm, colXs, subsectionID, position) values ('Checkboxes segmented element', @item_type_id, 12, 12, 12, 12, @subsection_id, 1);
set @item_id := last_insert_id();
insert into sg_element (upload1ID, upload2ID, upload3ID, upload4ID, upload5ID, baseID) values (@checkbox_checked_id, @checkbox_checked_ternary_id, @checkbox_unchecked_id, @checkbox_hover_id, @checkbox_disabled_id, @item_id);
set @id := last_insert_id();

insert into sg_subsection (name, description, sectionID, position) value ('Radio Buttons', null, @section_id, 3);
set @subsection_id := last_insert_id();

insert into sg_item (name, typeID, colLg, colMd, colSm, colXs, subsectionID, position) values ('Radio buttons segmented element', @item_type_id, 12, 12, 12, 12, @subsection_id, 1);
set @item_id := last_insert_id();
insert into sg_element (upload1ID, upload2ID, upload3ID, upload4ID, baseID) values (@radio_selected_id, @radio_unselected_id, @radio_hover_id, @radio_disabled_id, @item_id);
set @id := last_insert_id();

insert into sg_subsection (name, description, sectionID, position) value ('Toggle Switches', null, @section_id, 4);
set @subsection_id := last_insert_id();

insert into sg_item (name, typeID, colLg, colMd, colSm, colXs, subsectionID, position) values ('Toggle switches segmented element', @item_type_id, 12, 12, 12, 12, @subsection_id, 1);
set @item_id := last_insert_id();
insert into sg_element (upload1ID, upload2ID, upload3ID, upload4ID, baseID) values (@toggle_on_id, @toggle_off_id, @toggle_hover_id, @toggle_disabled_id, @item_id);
set @id := last_insert_id();

insert into sg_subsection (name, description, sectionID, position) value ('Buttons', null, @section_id, 5);
set @subsection_id := last_insert_id();

insert into sg_subsection (name, description, sectionID, position, parentSubsectionID) value ('Accent', null, @section_id, 1, @subsection_id);
set @subsubsection_id := last_insert_id();

insert into sg_item (name, typeID, colLg, colMd, colSm, colXs, subsectionID, position) values ('Buttons (accent, normal) segmented element', @item_type_id, 6, 6, 12, 12, @subsubsection_id, 1);
set @item_id := last_insert_id();
insert into sg_element (upload1ID, upload2ID, upload3ID, upload4ID, baseID) values (@button_normal_accent_id, @button_hover_accent_id, @button_active_accent_id, @button_disabled_accent_id, @item_id);
set @id := last_insert_id();

insert into sg_item (name, typeID, colLg, colMd, colSm, colXs, subsectionID, position) values ('Buttons (accent, dark) segmented element', @item_type_id, 6, 6, 12, 12, @subsubsection_id, 2);
set @item_id := last_insert_id();
insert into sg_element (upload1ID, upload2ID, upload3ID, upload4ID, baseID) values (@button_normal_dark_accent_id, @button_hover_dark_accent_id, @button_active_dark_accent_id, @button_disabled_dark_accent_id, @item_id);
set @id := last_insert_id();

insert into sg_subsection (name, description, sectionID, position, parentSubsectionID) value ('Main', null, @section_id, 3, @subsection_id);
set @subsubsection_id := last_insert_id();

insert into sg_item (name, typeID, colLg, colMd, colSm, colXs, subsectionID, position) values ('Buttons (main, normal) segmented element', @item_type_id, 6, 6, 12, 12, @subsubsection_id, 1);
set @item_id := last_insert_id();
insert into sg_element (upload1ID, upload2ID, upload3ID, upload4ID, baseID) values (@button_normal_main_id, @button_hover_main_id, @button_active_main_id, @button_disabled_main_id, @item_id);
set @id := last_insert_id();


insert into sg_item (name, typeID, colLg, colMd, colSm, colXs, subsectionID, position) values ('Buttons (main, dark) segmented element', @item_type_id, 6, 6, 12, 12, @subsubsection_id, 2);
set @item_id := last_insert_id();
insert into sg_element (upload1ID, upload2ID, upload3ID, upload4ID, baseID) values (@button_normal_dark_main_id, @button_hover_dark_main_id, @button_active_dark_main_id, @button_disabled_dark_main_id, @item_id);
set @id := last_insert_id();

insert into sg_subsection (name, description, sectionID, position) value ('Sliders', null, @section_id, 6);
set @subsection_id := last_insert_id();

insert into sg_item (name, typeID, colLg, colMd, colSm, colXs, subsectionID, position) values ('Sliders segmented element', @item_type_id, 12, 12, 12, 12, @subsection_id, 1);
set @item_id := last_insert_id();
insert into sg_element (upload1ID, upload2ID, upload3ID, upload4ID, baseID) values (@slider_enabled_id, @slider_two_input_disabled_id, @slider_hover_id, @slider_disabled_id, @item_id);
set @id := last_insert_id();

insert into sg_subsection (name, description, sectionID, position) value ('Text Boxes', null, @section_id, 7);
set @subsection_id := last_insert_id();

insert into sg_item (name, typeID, colLg, colMd, colSm, colXs, subsectionID, position) values ('Text boxes segmented element', @item_type_id, 12, 12, 12, 12, @subsection_id, 1);
set @item_id := last_insert_id();
insert into sg_element (upload1ID, upload2ID, upload3ID, upload4ID, upload5ID, baseID) values (@textbox_enabled_id, @textbox_placeholder_id, @textbox_hover_id, @textbox_error_id, @textbox_disabled_id, @item_id);
set @id := last_insert_id();

insert into sg_subsection (name, description, sectionID, position) value ('Dropdowns', null, @section_id, 8);
set @subsection_id := last_insert_id();

insert into sg_subsection (name, description, sectionID, position, parentSubsectionID) value ('Single-Select', null, @section_id, 1, @subsection_id);
set @subsubsection_id := last_insert_id();

insert into sg_item (name, typeID, colLg, colMd, colSm, colXs, subsectionID, position) values ('Dropdowns (single-select: closed, hover, disabled) segmented element', @item_type_id, 12, 12, 12, 12, @subsubsection_id, 1);
set @item_id := last_insert_id();
insert into sg_element (upload1ID, upload2ID, upload3ID, baseID) values (@dropdown_closed_id, @dropdown_hover_id, @dropdown_disabled_id, @item_id);
set @id := last_insert_id();

insert into sg_item (name, typeID, colLg, colMd, colSm, colXs, subsectionID, position) values ('Dropdowns (single-select: open, open up) segmented element', @item_type_id, 12, 12, 12, 12, @subsubsection_id, 2);
set @item_id := last_insert_id();
insert into sg_element (upload1ID, upload2ID, baseID) values (@dropdown_open_id, @dropdown_open_up_id, @item_id);
set @id := last_insert_id();

insert into sg_subsection (name, description, sectionID, position, parentSubsectionID) value ('Multi-Select', null, @section_id, 2, @subsection_id);
set @subsubsection_id := last_insert_id();

insert into sg_item (name, typeID, colLg, colMd, colSm, colXs, subsectionID, position) values ('Dropdowns (multi-select) segmented element', @item_type_id, 12, 12, 12, 12, @subsubsection_id, 1);
set @item_id := last_insert_id();
insert into sg_element (upload1ID, upload2ID, baseID) values (@dropdown_multi_closed_id, @dropdown_multi_open_id, @item_id);
set @id := last_insert_id();

insert into sg_subsection (name, description, sectionID, position) value ('Scrollbars', null, @section_id, 8);
set @subsection_id := last_insert_id();

insert into sg_item (name, typeID, colLg, colMd, colSm, colXs, subsectionID, position) values ('Scrollbars segmented element', @item_type_id, 12, 12, 12, 12, @subsection_id, 1);
set @item_id := last_insert_id();
insert into sg_element (upload1ID, upload2ID, upload3ID, upload4ID, baseID) values (@scrollbar_enabled_id, @scrollbar_light_id, @scrollbar_hover_id, @scrollbar_disabled_id, @item_id);
set @id := last_insert_id();

insert into sg_subsection (name, description, sectionID, position) value ('Time Inputs', null, @section_id, 9);
set @subsection_id := last_insert_id();

insert into sg_item (name, typeID, colLg, colMd, colSm, colXs, subsectionID, position) values ('Time inputs segmented element', @item_type_id, 12, 12, 12, 12, @subsection_id, 1);
set @item_id := last_insert_id();
insert into sg_element (upload1ID, upload2ID, upload3ID, baseID) values (@time_enabled_id, @time_hover_id, @time_disabled_id, @item_id);
set @id := last_insert_id();

insert into sg_subsection (name, description, sectionID, position) value ('Ratings', null, @section_id, 10);
set @subsection_id := last_insert_id();

insert into sg_item (name, typeID, colLg, colMd, colSm, colXs, subsectionID, position) values ('Ratings segmented element', @item_type_id, 12, 12, 12, 12, @subsection_id, 1);
set @item_id := last_insert_id();
insert into sg_element (upload1ID, upload2ID, baseID) values (@rating_enabled_id, @rating_disabled_id, @item_id);
set @id := last_insert_id();

insert into sg_subsection (name, description, sectionID, position) value ('Alerts', null, @section_id, 11);
set @subsection_id := last_insert_id();

insert into sg_item (name, typeID, colLg, colMd, colSm, colXs, subsectionID, position) values ('Alerts segmented element', @item_type_id, 12, 12, 12, 12, @subsection_id, 1);
set @item_id := last_insert_id();
insert into sg_element (upload1ID, upload2ID, baseID) values (@alert_success_id, @alert_error_id, @item_id);
set @id := last_insert_id();


select @section_id := id from sg_section where name = 'Molecules';
update sg_section set enabled = 1 where id = @section_id;

select @molecules_upload_id := id from sg_upload where filePath = 'molecules' and parentID is null;

insert into sg_upload (filePath, parentID, typeID) values ('tables', @molecules_upload_id, 1);
set @tables_upload_id := last_insert_id();

insert into sg_upload (parentID, filePath, typeID) values (@tables_upload_id, 'Dark.png', @file_type_id);
set @table_dark_id := last_insert_id();
insert into sg_upload_file (baseID, shortName, fullName) values (@table_dark_id, 'Dark', 'Table Dark');

insert into sg_subsection (name, description, sectionID, position) value ('Tables', null, @section_id, 1);
set @subsection_id := last_insert_id();

select @item_type_id := id from sg_item_type where code = 'elem-seg';

insert into sg_item (name, typeID, colLg, colMd, colSm, colXs, subsectionID, position) values ('Tables (dark) element', @item_type_id, 12, 12, 12, 12, @subsection_id, 1);
set @item_id := last_insert_id();
insert into sg_element (upload1ID, baseID) values (@table_dark_id, @item_id);
set @id := last_insert_id();


select @section_id := id from sg_section where name = 'Organisms';
update sg_section set enabled = 1 where id = @section_id;

select @organisms_upload_id := id from sg_upload where filePath = 'organisms' and parentID is null;

insert into sg_upload (filePath, parentID, typeID) values ('filters', @organisms_upload_id, 1);
set @filters_upload_id := last_insert_id();

insert into sg_upload (parentID, filePath, typeID) values (@filters_upload_id, 'Closed.png', @file_type_id);
set @filters_closed_id := last_insert_id();
insert into sg_upload_file (baseID, shortName, fullName) values (@filters_closed_id, 'Closed', 'Filters Closed');

insert into sg_upload (parentID, filePath, typeID) values (@filters_upload_id, 'Open.png', @file_type_id);
set @filters_open_id := last_insert_id();
insert into sg_upload_file (baseID, shortName, fullName) values (@filters_open_id, 'Open', 'Filters Open');

insert into sg_subsection (name, description, sectionID, position) value ('Filters', null, @section_id, 1);
set @subsection_id := last_insert_id();

select @item_type_id := id from sg_item_type where code = 'elem-seg';

insert into sg_item (name, typeID, colLg, colMd, colSm, colXs, subsectionID, position) values ('Filters (closed) element', @item_type_id, 12, 12, 12, 12, @subsection_id, 1);
set @item_id := last_insert_id();
insert into sg_element (upload1ID, baseID) values (@filters_closed_id, @item_id);
set @id := last_insert_id();

insert into sg_item (name, typeID, colLg, colMd, colSm, colXs, subsectionID, position) values ('Filters (open) element', @item_type_id, 12, 12, 12, 12, @subsection_id, 2);
set @item_id := last_insert_id();
insert into sg_element (upload1ID, baseID) values (@filters_open_id, @item_id);
set @id := last_insert_id();
