<?php

function omsc_get_fonawesome_list() {
	
	$arr=array(
		'glass',
		'music',
		'search',
		'envelope-o',
		'heart',
		'star',
		'star-o',
		'user',
		'film',
		'th-large',
		'th',
		'th-list',
		'check',
		'times',
		'search-plus',
		'search-minus',
		'power-off',
		'signal',
		'gear',
		'cog',
		'trash-o',
		'home',
		'file-o',
		'clock-o',
		'road',
		'download',
		'arrow-circle-o-down',
		'arrow-circle-o-up',
		'inbox',
		'play-circle-o',
		'rotate-right',
		'repeat',
		'refresh',
		'list-alt',
		'lock',
		'flag',
		'headphones',
		'volume-off',
		'volume-down',
		'volume-up',
		'qrcode',
		'barcode',
		'tag',
		'tags',
		'book',
		'bookmark',
		'print',
		'camera',
		'font',
		'bold',
		'italic',
		'text-height',
		'text-width',
		'align-left',
		'align-center',
		'align-right',
		'align-justify',
		'list',
		'dedent',
		'outdent',
		'indent',
		'video-camera',
		'picture-o',
		'pencil',
		'map-marker',
		'adjust',
		'tint',
		'edit',
		'pencil-square-o',
		'share-square-o',
		'check-square-o',
		'arrows',
		'step-backward',
		'fast-backward',
		'backward',
		'play',
		'pause',
		'stop',
		'forward',
		'fast-forward',
		'step-forward',
		'eject',
		'chevron-left',
		'chevron-right',
		'plus-circle',
		'minus-circle',
		'times-circle',
		'check-circle',
		'question-circle',
		'info-circle',
		'crosshairs',
		'times-circle-o',
		'check-circle-o',
		'ban',
		'arrow-left',
		'arrow-right',
		'arrow-up',
		'arrow-down',
		'mail-forward',
		'share',
		'expand',
		'compress',
		'plus',
		'minus',
		'asterisk',
		'exclamation-circle',
		'gift',
		'leaf',
		'fire',
		'eye',
		'eye-slash',
		'warning',
		'exclamation-triangle',
		'plane',
		'calendar',
		'random',
		'comment',
		'magnet',
		'chevron-up',
		'chevron-down',
		'retweet',
		'shopping-cart',
		'folder',
		'folder-open',
		'arrows-v',
		'arrows-h',
		'bar-chart-o',
		'twitter-square',
		'facebook-square',
		'camera-retro',
		'key',
		'gears',
		'cogs',
		'comments',
		'thumbs-o-up',
		'thumbs-o-down',
		'star-half',
		'heart-o',
		'sign-out',
		'linkedin-square',
		'thumb-tack',
		'external-link',
		'sign-in',
		'trophy',
		'github-square',
		'upload',
		'lemon-o',
		'phone',
		'square-o',
		'bookmark-o',
		'phone-square',
		'twitter',
		'facebook',
		'github',
		'unlock',
		'credit-card',
		'rss',
		'hdd-o',
		'bullhorn',
		'bell',
		'certificate',
		'hand-o-right',
		'hand-o-left',
		'hand-o-up',
		'hand-o-down',
		'arrow-circle-left',
		'arrow-circle-right',
		'arrow-circle-up',
		'arrow-circle-down',
		'globe',
		'wrench',
		'tasks',
		'filter',
		'briefcase',
		'arrows-alt',
		'group',
		'users',
		'chain',
		'link',
		'cloud',
		'flask',
		'cut',
		'scissors',
		'copy',
		'files-o',
		'paperclip',
		'save',
		'floppy-o',
		'square',
		'bars',
		'list-ul',
		'list-ol',
		'strikethrough',
		'underline',
		'table',
		'magic',
		'truck',
		'pinterest',
		'pinterest-square',
		'google-plus-square',
		'google-plus',
		'money',
		'caret-down',
		'caret-up',
		'caret-left',
		'caret-right',
		'columns',
		'unsorted',
		'sort',
		'sort-down',
		'sort-asc',
		'sort-up',
		'sort-desc',
		'envelope',
		'linkedin',
		'rotate-left',
		'undo',
		'legal',
		'gavel',
		'dashboard',
		'tachometer',
		'comment-o',
		'comments-o',
		'flash',
		'bolt',
		'sitemap',
		'umbrella',
		'paste',
		'clipboard',
		'lightbulb-o',
		'exchange',
		'cloud-download',
		'cloud-upload',
		'user-md',
		'stethoscope',
		'suitcase',
		'bell-o',
		'coffee',
		'cutlery',
		'file-text-o',
		'building-o',
		'hospital-o',
		'ambulance',
		'medkit',
		'fighter-jet',
		'beer',
		'h-square',
		'plus-square',
		'angle-double-left',
		'angle-double-right',
		'angle-double-up',
		'angle-double-down',
		'angle-left',
		'angle-right',
		'angle-up',
		'angle-down',
		'desktop',
		'laptop',
		'tablet',
		'mobile-phone',
		'mobile',
		'circle-o',
		'quote-left',
		'quote-right',
		'spinner',
		'circle',
		'mail-reply',
		'reply',
		'github-alt',
		'folder-o',
		'folder-open-o',
		'smile-o',
		'frown-o',
		'meh-o',
		'gamepad',
		'keyboard-o',
		'flag-o',
		'flag-checkered',
		'terminal',
		'code',
		'reply-all',
		'mail-reply-all',
		'star-half-empty',
		'star-half-full',
		'star-half-o',
		'location-arrow',
		'crop',
		'code-fork',
		'unlink',
		'chain-broken',
		'question',
		'info',
		'exclamation',
		'superscript',
		'subscript',
		'eraser',
		'puzzle-piece',
		'microphone',
		'microphone-slash',
		'shield',
		'calendar-o',
		'fire-extinguisher',
		'rocket',
		'maxcdn',
		'chevron-circle-left',
		'chevron-circle-right',
		'chevron-circle-up',
		'chevron-circle-down',
		'html5',
		'css3',
		'anchor',
		'unlock-alt',
		'bullseye',
		'ellipsis-h',
		'ellipsis-v',
		'rss-square',
		'play-circle',
		'ticket',
		'minus-square',
		'minus-square-o',
		'level-up',
		'level-down',
		'check-square',
		'pencil-square',
		'external-link-square',
		'share-square',
		'compass',
		'toggle-down',
		'caret-square-o-down',
		'toggle-up',
		'caret-square-o-up',
		'toggle-right',
		'caret-square-o-right',
		'euro',
		'eur',
		'gbp',
		'dollar',
		'usd',
		'rupee',
		'inr',
		'cny',
		'rmb',
		'yen',
		'jpy',
		'ruble',
		'rouble',
		'rub',
		'won',
		'krw',
		'bitcoin',
		'btc',
		'file',
		'file-text',
		'sort-alpha-asc',
		'sort-alpha-desc',
		'sort-amount-asc',
		'sort-amount-desc',
		'sort-numeric-asc',
		'sort-numeric-desc',
		'thumbs-up',
		'thumbs-down',
		'youtube-square',
		'youtube',
		'xing',
		'xing-square',
		'youtube-play',
		'dropbox',
		'stack-overflow',
		'instagram',
		'flickr',
		'adn',
		'bitbucket',
		'bitbucket-square',
		'tumblr',
		'tumblr-square',
		'long-arrow-down',
		'long-arrow-up',
		'long-arrow-left',
		'long-arrow-right',
		'apple',
		'windows',
		'android',
		'linux',
		'dribbble',
		'skype',
		'foursquare',
		'trello',
		'female',
		'male',
		'gittip',
		'sun-o',
		'moon-o',
		'archive',
		'bug',
		'vk',
		'weibo',
		'renren',
		'pagelines',
		'stack-exchange',
		'arrow-circle-o-right',
		'arrow-circle-o-left',
		'toggle-left',
		'caret-square-o-left',
		'dot-circle-o',
		'wheelchair',
		'vimeo-square',
		'turkish-lira',
		'try',
		'plus-square-o',
	);
	
	sort($arr);
	return $arr;
	
}

function omsc_get_fonawesome_backward_aliases() {
	
	$arr=array(
		'ban-circle'=>'ban',
		'bar-chart'=>'bar-chart-o',
		'beaker'=>'flask',
		'bell'=>'bell-o',
		'bell-alt'=>'bell',
		'bitbucket-sign'=>'bitbucket-square',
		'bookmark-empty'=>'bookmark-o',
		'building'=>'building-o (4.0.2)',
		'calendar-empty'=>'calendar-o',
		'check-empty'=>'square-o',
		'check-minus'=>'minus-square-o',
		'check-sign'=>'check-square',
		'check'=>'check-square-o',
		'chevron-sign-down'=>'chevron-circle-down',
		'chevron-sign-left'=>'chevron-circle-left',
		'chevron-sign-right'=>'chevron-circle-right',
		'chevron-sign-up'=>'chevron-circle-up',
		'circle-arrow-down'=>'arrow-circle-down',
		'circle-arrow-left'=>'arrow-circle-left',
		'circle-arrow-right'=>'arrow-circle-right',
		'circle-arrow-up'=>'arrow-circle-up',
		'circle-blank'=>'circle-o',
		'cny'=>'rub',
		'collapse-alt'=>'minus-square-o',
		'collapse-top'=>'caret-square-o-up',
		'collapse'=>'caret-square-o-down',
		'comment-alt'=>'comment-o',
		'comments-alt'=>'comments-o',
		'copy'=>'files-o',
		'cut'=>'scissors',
		'dashboard'=>'tachometer',
		'double-angle-down'=>'angle-double-down',
		'double-angle-left'=>'angle-double-left',
		'double-angle-right'=>'angle-double-right',
		'double-angle-up'=>'angle-double-up',
		'download'=>'arrow-circle-o-down',
		'download-alt'=>'download',
		'edit-sign'=>'pencil-square',
		'edit'=>'pencil-square-o',
		'ellipsis-horizontal'=>'ellipsis-h (4.0.2)',
		'ellipsis-vertical'=>'ellipsis-v (4.0.2)',
		'envelope-alt'=>'envelope-o',
		'exclamation-sign'=>'exclamation-circle',
		'expand-alt'=>'expand-o',
		'expand'=>'caret-square-o-right',
		'external-link-sign'=>'external-link-square',
		'eye-close'=>'eye-slash',
		'eye-open'=>'eye',
		'facebook-sign'=>'facebook-square',
		'facetime-video'=>'video-camera',
		'file-alt'=>'file-o',
		'file-text-alt'=>'file-text-o',
		'flag-alt'=>'flag-o',
		'folder-close-alt'=>'folder-o',
		'folder-close'=>'folder',
		'folder-open-alt'=>'folder-open-o',
		'food'=>'cutlery',
		'frown'=>'frown-o',
		'fullscreen'=>'arrows-alt (4.0.2)',
		'github-sign'=>'github-square',
		'google-plus-sign'=>'google-plus-square',
		'group'=>'users (4.0.2)',
		'h-sign'=>'h-square',
		'hand-down'=>'hand-o-down',
		'hand-left'=>'hand-o-left',
		'hand-right'=>'hand-o-right',
		'hand-up'=>'hand-o-up',
		'hdd'=>'hdd-o (4.0.1)',
		'heart-empty'=>'heart-o',
		'hospital'=>'hospital-o (4.0.2)',
		'indent-left'=>'outdent',
		'indent-right'=>'indent',
		'info-sign'=>'info-circle',
		'keyboard'=>'keyboard-o',
		'legal'=>'gavel',
		'lemon'=>'lemon-o',
		'lightbulb'=>'lightbulb-o',
		'linkedin-sign'=>'linkedin-square',
		'meh'=>'meh-o',
		'microphone-off'=>'microphone-slash',
		'minus-sign-alt'=>'minus-square',
		'minus-sign'=>'minus-circle',
		'mobile-phone'=>'mobile',
		'moon'=>'moon-o',
		'move'=>'arrows (4.0.2)',
		'off'=>'power-off',
		'ok-circle'=>'check-circle-o',
		'ok-sign'=>'check-circle',
		'ok'=>'check',
		'paper-clip'=>'paperclip',
		'paste'=>'clipboard',
		'phone-sign'=>'phone-square',
		'picture'=>'picture-o',
		'pinterest-sign'=>'pinterest-square',
		'play-circle'=>'play-circle-o',
		'play-sign'=>'play-circle',
		'plus-sign-alt'=>'plus-square',
		'plus-sign'=>'plus-circle',
		'pushpin'=>'thumb-tack',
		'question-sign'=>'question-circle',
		'remove-circle'=>'times-circle-o',
		'remove-sign'=>'times-circle',
		'remove'=>'times',
		'reorder'=>'bars (4.0.2)',
		'resize-full'=>'expand (4.0.2)',
		'resize-horizontal'=>'arrows-h (4.0.2)',
		'resize-small'=>'compress (4.0.2)',
		'resize-vertical'=>'arrows-v (4.0.2)',
		'rss-sign'=>'rss-square',
		'save'=>'floppy-o',
		'screenshot'=>'crosshairs',
		'share-alt'=>'share',
		'share-sign'=>'share-square',
		'share'=>'share-square-o',
		'sign-blank'=>'square',
		'signin'=>'sign-in',
		'signout'=>'sign-out',
		'smile'=>'smile-o',
		'sort-by-alphabet-alt'=>'sort-alpha-desc',
		'sort-by-alphabet'=>'sort-alpha-asc',
		'sort-by-attributes-alt'=>'sort-amount-desc',
		'sort-by-attributes'=>'sort-amount-asc',
		'sort-by-order-alt'=>'sort-numeric-desc',
		'sort-by-order'=>'sort-numeric-asc',
		'sort-down'=>'sort-asc',
		'sort-up'=>'sort-desc',
		'stackexchange'=>'stack-overflow',
		'star-empty'=>'star-o',
		'star-half-empty'=>'star-half-o',
		'sun'=>'sun-o',
		'thumbs-down-alt'=>'thumbs-o-down',
		'thumbs-up-alt'=>'thumbs-o-up',
		'time'=>'clock-o',
		'trash'=>'trash-o',
		'tumblr-sign'=>'tumblr-square',
		'twitter-sign'=>'twitter-square',
		'unlink'=>'chain-broken',
		'upload'=>'arrow-circle-o-up',
		'upload-alt'=>'upload',
		'warning-sign'=>'exclamation-triangle',
		'xing-sign'=>'xing-square',
		'youtube-sign'=>'youtube-square',
		'zoom-in'=>'search-plus',
		'zoom-out'=>'search-minus',
	);
	
	return $arr;
}