<?php

/**
 * @file login_redirect.addon.php
 * @author Kijin Sung <kijin@kijinsung.com>
 * @license GPLv2 or Later <https://www.gnu.org/licenses/gpl-2.0.html>
 */
if (!defined('__XE__')) exit();

/**
 * Detect duplicates before module action.
 */
if ($called_position === 'before_module_init' && $addon_info->redirect_url)
{
	$logged_info = Context::get('logged_info');
	if (!$logged_info || !$logged_info->member_srl)
	{
		$addon_info->redirect_url = trim($addon_info->redirect_url);
		if (preg_match('/^(?:disp|proc)Member/', Context::get('act')))
		{
			return;
		}
		if (preg_match('/^[a-z0-9_]+$/i', $addon_info->redirect_url) && Context::get('mid') === $addon_info->redirect_url)
		{
			return;
		}
		
		$redirect_url_info = parse_url($addon_info->redirect_url);
		if ($_SERVER['HTTP_HOST'] === $redirect_url_info['host'] && $_SERVER['REQUEST_URI'] === $redirect_url_info['path'])
		{
			return;
		}
		if ($_SERVER['REQUEST_URI'] === $redirect_url_info['path'])
		{
			return;
		}
		
		if (!headers_sent())
		{
			header('Location: ' . $addon_info->redirect_url);
			exit;
		}
	}
}
