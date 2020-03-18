import React, {useState, useContext} from 'react';
import {useParams} from 'react-router-dom';
import styled from 'styled-components';
import {useMeasurementFamily} from 'akeneomeasure/hooks/use-measurement-families';
import {TranslateContext} from 'akeneomeasure/context/translate-context';
import {UnitTab} from 'akeneomeasure/pages/edit/UnitTab';
import {PropertyTab} from 'akeneomeasure/pages/edit/PropertyTab';
import {PageHeader, PageHeaderPlaceholder} from 'akeneomeasure/shared/components/PageHeader';
import {PimView} from 'akeneomeasure/bridge/legacy/pim-view/PimView';
import {Breadcrumb} from 'akeneomeasure/shared/components/Breadcrumb';
import {BreadcrumbItem} from 'akeneomeasure/shared/components/BreadcrumbItem';
import {Button} from 'akeneomeasure/shared/components/Button';
import {getMeasurementFamilyLabel, MeasurementFamily} from 'akeneomeasure/model/measurement-family';
import {UserContext} from 'akeneomeasure/context/user-context';
import {PageContent} from 'akeneomeasure/shared/components/PageContent';

enum Tab {
  Units = 'units',
  Properties = 'properties',
}

const TabContainer = styled.div`
  display: flex;
  width: 100%;
  border-bottom: 1px solid ${props => props.theme.color.grey80};
`;

const TabSelector = styled.div<{isActive: boolean}>`
  width: 90px;
  padding: 13px 0;
  cursor: pointer;
  font-size: ${props => props.theme.fontSize.big};
  color: ${props => (props.isActive ? props.theme.color.purple100 : 'inherit')};
  border-bottom: 3px solid ${props => (props.isActive ? props.theme.color.purple100 : 'transparent')};
`;

const Edit = () => {
  const __ = useContext(TranslateContext);
  const locale = useContext(UserContext)('uiLocale');
  const {measurementFamilyCode} = useParams() as {measurementFamilyCode: string};
  const [currentTab, setCurrentTab] = useState<Tab>(Tab.Units);
  const [measurementFamily, setMeasurementFamily] = useMeasurementFamily(measurementFamilyCode);

  const onMeasurementFamilyChange = (newMeasurementFamily: MeasurementFamily) => {
    setMeasurementFamily(newMeasurementFamily);
  };

  if (undefined === measurementFamilyCode || null === measurementFamily) {
    return null;
  }

  return (
    <>
      <PageHeader
        userButtons={
          <PimView
            className="AknTitleContainer-userMenuContainer AknTitleContainer-userMenu"
            viewName="pim-measurements-user-navigation"
          />
        }
        buttons={[
          <Button
            onClick={() => {
              //TODO save
            }}
          >
            {__('pim_common.save')}
          </Button>,
        ]}
        breadcrumb={
          <Breadcrumb>
            <BreadcrumbItem>{__('pim_menu.tab.settings')}</BreadcrumbItem>
            <BreadcrumbItem>{__('pim_menu.item.measurements')}</BreadcrumbItem>
          </Breadcrumb>
        }
      >
        {null === measurementFamily ? (
          <div className={`AknLoadingPlaceHolderContainer`}>
            <PageHeaderPlaceholder />
          </div>
        ) : (
          <div>{getMeasurementFamilyLabel(measurementFamily, locale)}</div>
        )}
      </PageHeader>

      <PageContent>
        <TabContainer>
          {Object.values(Tab).map((tab: Tab) => (
            <TabSelector key={tab} onClick={() => setCurrentTab(tab)} isActive={currentTab === tab}>
              {__(`measurements.family.tab.${tab}`)}
            </TabSelector>
          ))}
        </TabContainer>
        {currentTab === Tab.Units && (
          <UnitTab measurementFamily={measurementFamily} onMeasurementFamilyChange={onMeasurementFamilyChange} />
        )}
        {currentTab === Tab.Properties && (
          <PropertyTab measurementFamily={measurementFamily} onMeasurementFamilyChange={onMeasurementFamilyChange} />
        )}
      </PageContent>
    </>
  );
};

export {Edit};