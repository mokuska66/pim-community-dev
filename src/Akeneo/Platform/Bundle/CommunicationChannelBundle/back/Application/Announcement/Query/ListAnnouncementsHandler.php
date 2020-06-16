<?php

declare(strict_types=1);

namespace Akeneo\Platform\CommunicationChannel\Application\Announcement\Query;

use Akeneo\Platform\CommunicationChannel\Domain\Announcement\Model\Read\AnnouncementItem;
use Akeneo\Platform\CommunicationChannel\Domain\Announcement\Query\FindAnnouncementItemsInterface;

/**
 * @author Christophe Chausseray <chaauseray.christophe@gmail.com>
 * @copyright 2020 Akeneo SAS (http://www.akeneo.com)
 * @license http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */
final class ListAnnouncementsHandler
{
    /** @var FindAnnouncementItemsInterface */
    private  $findAnnouncementItemsInterface;

    public function __construct(FindAnnouncementItemsInterface $findAnnouncementItemsInterface)
    {
        $this->findAnnouncementItemsInterface = $findAnnouncementItemsInterface;
    }

    public function execute(): array
    {
        $announcementItems = $this->findAnnouncementItemsInterface->byPimVersion('', '');

        $normalizedAnnouncementItems = $this->normalizeAnnouncementItems($announcementItems);

        return [
            'items' => $normalizedAnnouncementItems,
        ];
    }

    private function normalizeAnnouncementItems(array $announcementItems): array
    {
        return array_map(function (AnnouncementItem $item) {
            return $item->normalize();
        }, $announcementItems);
    }
}
