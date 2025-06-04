<?php

declare(strict_types=1);

namespace JaroslawZielinski\Diagnostics\Model\Console\CustomerWithoutPassword;

use JaroslawZielinski\Diagnostics\Model\Test\Test;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerExtensionFactory;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Model\Customer;
use Magento\Customer\Model\CustomerFactory;
use Magento\Customer\Model\EmailNotificationInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

class Create extends Test
{
    public const STORE_ID = 'store_id';

    public const EMAIL = 'email';

    public const FIRST_NAME = 'firstname';

    public const LAST_NAME = 'lastname';

    public const PREFIX = 'prefix';

    public const MOBILE_PHONE = 'mobilephone';

    /**
     * @var CustomerFactory
     */
    private $customerFactory;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepository;

    /**
     * @var CustomerExtensionFactory
     */
    private $customerExtensionFactory;

    /**
     * @var EmailNotificationInterface
     */
    private $emailNotification;

    /**
     * @var OutputInterface
     */
    private $output;

    /**
     * @inheritDoc
     */
    public function __construct(
        CustomerFactory $customerFactory,
        StoreManagerInterface $storeManager,
        CustomerRepositoryInterface $customerRepository,
        CustomerExtensionFactory $customerExtensionFactory,
        EmailNotificationInterface $emailNotification,
        LoggerInterface $logger
    ) {
        $this->customerFactory = $customerFactory;
        $this->storeManager = $storeManager;
        $this->customerRepository = $customerRepository;
        $this->customerExtensionFactory = $customerExtensionFactory;
        $this->emailNotification = $emailNotification;
        parent::__construct($logger);
    }

    /**
     * {@inheritDoc}
     * @throws \Exception
     */
    public function execute(array $input, OutputInterface $output): array
    {
        $storeId = $input[self::STORE_ID];
        if (empty($storeId)) {
            throw new LocalizedException(__('Write store ID first.'));
        }
        $email = $input[self::EMAIL];
        if (empty($email)) {
            throw new LocalizedException(__('Write e-mail first.'));
        }
        $prefix = $input[self::PREFIX];
        if (empty($prefix)) {
            throw new LocalizedException(__('Write prefix first.'));
        }
        $firstName = $input[self::FIRST_NAME];
        if (empty($firstName)) {
            throw new LocalizedException(__('Write first name first.'));
        }
        $lastName = $input[self::LAST_NAME];
        if (empty($lastName)) {
            throw new LocalizedException(__('Write last name first.'));
        }
        $mobilePhone = $input[self::MOBILE_PHONE];
        if (empty($mobilePhone)) {
            throw new LocalizedException(__('Write mobile phone first.'));
        }
        $this->output = $output;
        try {
            $store = $this->storeManager->getStore($storeId);
            $website = $this->storeManager->getStore($store)->getWebsite();
            $websiteId = $website->getId();
            $customerModel = $this->customerFactory->create();
            /** @var CustomerInterface|Customer $loadCustomer */
            $customerDataModel = $customerModel->getDataModel();
            $customerDataModel
                ->setWebsiteId($websiteId)
                ->setEmail($email)
                ->setPrefix($prefix)
                ->setFirstname($firstName)
                ->setLastname($lastName);
            $extensionAttributes = $customerDataModel->getExtensionAttributes();
            if (!$extensionAttributes) {
                $extensionAttributes = $this->customerExtensionFactory->create();
            }
            $extensionAttributes->setMobilePhone($mobilePhone);
            $customerDataModel->setExtensionAttributes($extensionAttributes);
            $customerDataModel = $this->customerRepository->save($customerDataModel);
            $this->emailNotification->newAccount(
                $customerDataModel,
                EmailNotificationInterface::NEW_ACCOUNT_EMAIL_REGISTERED_NO_PASSWORD,
                $backUrl = '',
                $storeId,
                $storeId
            );
        } catch (\Exception|LocalizedException $e) {
            $this->logger->error($e->getMessage(), $e->getTrace());
            return [
                __('Something went wrong [message: %1]', $e->getMessage())
            ];
        }
        return [
            __('OK. Customer with \'%1\' created.', $email)
        ];
    }

    /**
     * @inheritDoc
     */
    public function getArgumentsDefinition(): array
    {
        return [
            [
                'name' => self::STORE_ID,
                'mode' => InputArgument::REQUIRED,
                'description' => (string)__('Store ID'),
                'default' => null
            ],
            [
                'name' => self::EMAIL,
                'mode' => InputArgument::REQUIRED,
                'description' => (string)__('Customer Email'),
                'default' => null
            ],
            [
                'name' => self::PREFIX,
                'mode' => InputArgument::REQUIRED,
                'description' => (string)__('Prefix'),
                'default' => null
            ],
            [
                'name' => self::FIRST_NAME,
                'mode' => InputArgument::REQUIRED,
                'description' => (string)__('First Name'),
                'default' => null
            ],
            [
                'name' => self::LAST_NAME,
                'mode' => InputArgument::REQUIRED,
                'description' => (string)__('Last Name'),
                'default' => null
            ],
            [
                'name' => self::MOBILE_PHONE,
                'mode' => InputArgument::REQUIRED,
                'description' => (string)__('Mobile Phone'),
                'default' => null
            ]
        ];
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return 'jaroslawzielinski:customer-without-password:create';
    }

    /**
     * @inheritDoc
     */
    public function getDescription(): string
    {
        return 'Creates Customer Without Password';
    }
}
