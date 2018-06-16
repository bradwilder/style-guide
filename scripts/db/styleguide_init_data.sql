insert into sg_section (name, position, enabled) value ('Colors', 1, 0);
insert into sg_section (name, position, enabled) value ('Typography', 2, 0);
insert into sg_section (name, position, enabled) value ('Elements', 3, 0);
insert into sg_section (name, position, enabled) value ('Molecules', 4, 0);
insert into sg_section (name, position, enabled) value ('Organisms', 5, 0);
insert into sg_section (name, position, enabled) value ('Layouts', 6, 0);
insert into sg_section (name, position, enabled) value ('Sitemaps', 7, 0);

insert into sg_item_type (description, code) values ('Color Variant Swatch With Descriptors', 'color-var-desc');
set @id := last_insert_id();
insert into sg_item_type_column_min (typeID, minLG, minMD, minSM, minXS) values (@id, 4, 5, 7, 12);

insert into sg_item_type (description, code) values ('Color Variant Swatch', 'color-var');
set @id := last_insert_id();
insert into sg_item_type_column_min (typeID, minLG, minMD, minSM, minXS) values (@id, 2, 3, 4, 6);

insert into sg_item_type (description, code) values ('Color Tile With Descriptors', 'color-desc');
set @id := last_insert_id();
insert into sg_item_type_column_min (typeID, minLG, minMD, minSM, minXS) values (@id, 3, 4, 5, 8);

insert into sg_item_type (description, code) values ('Color Tile', 'color');
set @id := last_insert_id();
insert into sg_item_type_column_min (typeID, minLG, minMD, minSM, minXS) values (@id, 1, 2, 2, 3);

insert into sg_item_type (description, code) values ('Font Family Card', 'font-fmy');
set @id := last_insert_id();
insert into sg_item_type_column_min (typeID, minLG, minMD, minSM, minXS) values (@id, 4, 5, 6, 12);

insert into sg_item_type (description, code) values ('Font Usage Table', 'font-tbl');
set @id := last_insert_id();
insert into sg_item_type_column_min (typeID, minLG, minMD, minSM, minXS) values (@id, 4, 6, 8, 12);

insert into sg_item_type (description, code) values ('CSS Icons', 'icons-css');
set @id := last_insert_id();
insert into sg_item_type_column_min (typeID, minLG, minMD, minSM, minXS) values (@id, 1, 2, 2, 3);

insert into sg_item_type (description, code) values ('Segmented Element', 'elem-seg');
set @id := last_insert_id();
insert into sg_item_type_column_min (typeID, code, minLG, minMD, minSM, minXS) values (@id, '1', 1, 2, 2, 3);
insert into sg_item_type_column_min (typeID, code, minLG, minMD, minSM, minXS) values (@id, '2', 2, 3, 3, 4);
insert into sg_item_type_column_min (typeID, code, minLG, minMD, minSM, minXS) values (@id, '3', 3, 3, 5, 6);
insert into sg_item_type_column_min (typeID, code, minLG, minMD, minSM, minXS) values (@id, '4', 3, 4, 6, 8);
insert into sg_item_type_column_min (typeID, code, minLG, minMD, minSM, minXS) values (@id, '5', 4, 5, 7, 12);
insert into sg_item_type_column_min (typeID, code, minLG, minMD, minSM, minXS) values (@id, '6', 5, 6, 8, 12);

insert into sg_font_type (description, code) value ('Web font', 'web');
insert into sg_font_type (description, code) value ('CSS font', 'css');

insert into sg_font_alphabet (name, code, alphabet) values ('Standard English', 'std', 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890‘?’“!”(%)[#]{@}/&<-+÷×=>®©$€£¥¢:;,.*\|^~`_');
insert into sg_font_alphabet (name, code, alphabet) values ('English, Cyrillic, and Greek', 'cyrgrk', 'ABCĆČDĐEFGHIJKLMNOPQRSŠTUVWXYZŽabcčćdđefghijklmnopqrsštuvwxyzžАБВГҐДЂЕЁЄЖЗЅИІЇЙЈКЛЉМНЊОПРСТЋУЎФХЦЧЏШЩЪЫЬЭЮЯабвгґдђеёєжзѕиіїйјклљмнњопрстћуўфхцчџшщъыьэюяΑΒΓΔΕΖΗΘΙΚΛΜΝΞΟΠΡΣΤΥΦΧΨΩαβγδεζηθικλμνξοπρστυφχψωάΆέΈέΉίϊΐΊόΌύΰϋΎΫὰάὲέὴήὶίὸόὺύὼώΏĂÂÊÔƠƯăâêôơư1234567890‘?’“!”(%)[#]{@}/&<-+÷×=>®©$€£¥¢:;,.*\|^~`_');
insert into sg_font_alphabet (name, code, alphabet) values ('English and Cyrillic', 'cyr', 'ABCĆČDĐEFGHIJKLMNOPQRSŠTUVWXYZŽabcčćdđefghijklmnopqrsštuvwxyzžАБВГҐДЂЕЁЄЖЗЅИІЇЙЈКЛЉМНЊОПРСТЋУЎФХЦЧЏШЩЪЫЬЭЮЯабвгґдђеёєжзѕиіїйјклљмнњопрстћуўфхцчџшщъыьэюя1234567890‘?’“!”(%)[#]{@}/&<-+÷×=>®©$€£¥¢:;,.*\|^~`_');
insert into sg_font_alphabet (name, code, alphabet) values ('English upper-case', 'upper', 'ABCDEFGHIJKLMNOPQRSTUVWXYZ');

insert into sg_upload_type (code) values ('folder');
set @folderID := last_insert_id();
insert into sg_upload_type (code) values ('file');

insert into sg_upload (filePath, parentID, typeID) values ('elements', null, @folderID);
insert into sg_upload (filePath, parentID, typeID) values ('molecules', null, @folderID);
insert into sg_upload (filePath, parentID, typeID) values ('organisms', null, @folderID);
insert into sg_upload (filePath, parentID, typeID) values ('layouts', null, @folderID);
insert into sg_upload (filePath, parentID, typeID) values ('sitemaps', null, @folderID);