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

if (!empty($invalidElectronicPortfolios)) : ?>
    <div class="table-responsive">
        <table id="portfolio-datatable" class="table table-striped">
            <thead>
                <tr>
                    <th>
                        <?php echo LanguageService::getLabel('table_field_name_mms_id'); ?>
                    </th>
                    <th>
                        <?php echo LanguageService::getLabel('table_field_name_title'); ?>
                    </th>
                    <th>
                        <?php echo LanguageService::getLabel('table_field_name_static_url'); ?>
                    </th>
                    <th>
                        <?php echo LanguageService::getLabel('table_field_name_e_collection_name'); ?>
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
                <?php foreach ($invalidElectronicPortfolios as $portfolio) : ?>
                    <tr>
                        <td>
                            <?php echo strip_tags($portfolio['resource_metadata']['mms_id']['value']); ?>
                        </td>
                        <td>
                            <?php echo strip_tags($portfolio['resource_metadata']['title']); ?>
                        </td>
                        <td style="width: 250px;">
                            <?php if (!empty($portfolio['linking_details']['static_url'])) : ?>
                                <?php $url = str_replace('jkey=', '', $portfolio['linking_details']['static_url']); ?>
                                <a target="_blank" href="<?php echo urlencode($url); ?>">
                                    <?php echo $url; ?>
                                </a>
                            <?php endif; ?>
                            <?php if (!empty($portfolio['linking_details']['static_url_override'])) : ?>
                                <?php $url = str_replace('jkey=', '', $portfolio['linking_details']['static_url_override']); ?>
                                <a target="_blank" href="<?php echo urlencode($url); ?>">
                                    <?php echo $url; ?>
                                </a>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php echo $portfolio['electronic_collection_name']; ?>
                        </td>
                        <td style="width: 290px;">
                            <ul class="mb-0">
                                <li>
                                    <?php echo LanguageService::getLabel('hint_multiple_redirections'); ?>
                                </li>
                                <?php if (
                                    !empty($portfolio['linking_details']['static_url']) &&
                                    !str_contains($portfolio['linking_details']['static_url'], 'https://')
                                ) : ?>
                                    <li>
                                        <?php echo LanguageService::getLabel('hint_http_to_https_redirection'); ?>
                                    </li>
                                <?php endif; ?>
                                <?php if (
                                    !empty($portfolio['linking_details']['static_url_override']) &&
                                    !str_contains($portfolio['linking_details']['static_url_override'], 'https://')
                                ) : ?>
                                    <li>
                                        <?php echo LanguageService::getLabel('hint_http_to_https_redirection'); ?>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </td>
                        <td style="width: 350px;" class="text-end">
                            <input type="text" placeholder="<?php echo LanguageService::getLabel('placeholder_new_url'); ?>" class="form-control" required />
                            <a href="" data-is-url-override="<?php echo (empty($portfolio['linking_details']['static_url']) && !empty($portfolio['linking_details']['static_url_override']) ? 1 : 0); ?>" data-collection-id="<?php echo $portfolio['electronic_collection']['id']['value']; ?>" data-service-id="<?php echo $portfolio['electronic_collection']['service']['value']; ?>" data-portfolio-id="<?php echo $portfolio['id']; ?>" class="btn btn-primary mt-2 portfolio-update-button">
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