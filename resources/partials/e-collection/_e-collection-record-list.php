<?php

/*
 * This file is part of the Link Checker package.
 *
 * (c) ZHAW HSB <apps.hsb@zhaw.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use App\Service\LanguageService;

if (!empty($invalidElectronicCollections)) : ?>
    <div class="table-responsive">
        <table id="collection-datatable" class="table table-striped">
            <thead>
                <tr>
                    <th>
                        <?php echo LanguageService::getLabel('table_field_name_mms_id'); ?>
                    </th>
                    <th>
                        <?php echo LanguageService::getLabel('table_field_name_type'); ?>
                    </th>
                    <th>
                        <?php echo LanguageService::getLabel('table_field_name_name'); ?>
                    </th>
                    <th>
                        <?php echo LanguageService::getLabel('table_field_name_url'); ?>
                    </th>
                    <th>
                        <?php echo LanguageService::getLabel('table_field_name_error_source'); ?>
                    </th>
                    <th>
                        <?php echo LanguageService::getLabel('table_field_name_new_url'); ?>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($invalidElectronicCollections as $collection) : ?>
                    <tr>
                        <td>
                            <?php echo strip_tags($collection['resource_metadata']['mms_id']['value']); ?>
                        </td>
                        <td>
                            <?php echo strip_tags($collection['type']['desc']); ?>
                        </td>
                        <td>
                            <?php echo strip_tags($collection['public_name']); ?>
                        </td>
                        <td style="width: 310px;">
                            <?php if (!empty($collection['url'])) : ?>
                                <?php $url =  $collection['url']; ?>
                                <a target="_blank" href="<?php echo urlencode($url); ?>">
                                    <?php echo $url; ?>
                                </a>
                            <?php endif; ?>
                            <?php if (!empty($collection['url_override'])) : ?>
                                <?php $url = $collection['url_override']; ?>
                                <a target="_blank" href="<?php echo urlencode($url); ?>">
                                    <?php echo $url; ?>
                                </a>
                            <?php endif; ?>
                        </td>
                        <td style="width: 290px;">
                            <ul class="mb-0">
                                <li>
                                    <?php echo LanguageService::getLabel('hint_multiple_redirections'); ?>
                                </li>
                                <?php if (
                                    !empty($collection['url']) &&
                                    !str_contains($collection['url'], 'https://')
                                ) : ?>
                                    <li>
                                        <?php echo LanguageService::getLabel('hint_http_to_https_redirection'); ?>
                                    </li>
                                <?php endif; ?>
                                <?php if (
                                    !empty($collection['url_override']) &&
                                    !str_contains($collection['url_override'], 'https://')
                                ) : ?>
                                    <li>
                                        <?php echo LanguageService::getLabel('hint_http_to_https_redirection'); ?>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </td>
                        <td style="width: 350px;" class="text-end">
                            <input type="text" placeholder="<?php echo LanguageService::getLabel('placeholder_new_url'); ?>" class="form-control" required />
                            <a href="" data-is-url-override="<?php echo (empty($collection['url']) && !empty($collection['url_override']) ? 1 : 0); ?>" data-collection-id="<?php echo $collection['id']; ?>" data-public-name="<?php echo $collection['public_name']; ?>" data-type='<?php echo implode(',', $collection['type']); ?>' class="btn btn-primary mt-2 collection-update-button">
                                <?php echo LanguageService::getLabel('button_update'); ?>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else : ?>
    <p>
        <?php echo LanguageService::getLabel('notice_no_entries'); ?>
    </p>
<?php endif; ?>